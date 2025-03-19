<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Blog/List', [
            'blogs' => Blog::with('poster', 'comments')->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Blog/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:5',
        ]);
        
        $blog = Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'poster_id' => Auth::id(),
        ]);

        return to_route('blog.show', $blog);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return to_route('admin.blogs.edit', $blog->load('poster', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return Inertia::render('Admin/Blog/Edit', [
            'blog' => $blog->load('poster', 'comments'),
            'users' => User::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:5',
            'author' => 'required',
        ]);

        $blog->title = $request->title;
        $blog->content = $request->content;
        if($request->author != null) {        
            $blog->poster_id = $request->author; 
        }

        $blog->save();

        return to_route('admin.blogs.edit', $blog->load('poster'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, Request $request)
    {
        $request->validate([
            'confirm_title' => 'required|string',
        ]);

        if($request->confirm_title == $blog->title) {
            $blog->delete();
        }
        else {
            return back()->withErrors(['confirm_title' => 'Title does not match.']);
        }

        return to_route('admin.blogs.index');
    }
}
