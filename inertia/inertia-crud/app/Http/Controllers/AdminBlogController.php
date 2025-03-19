<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Blog/List', [
            'blogs' => Blog::with('poster')->latest()->paginate(10),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {

        return to_route('admin.blogs.edit', $blog->load('poster'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return Inertia::render('Admin/Blog/Edit', [
            'blog' => $blog->load('poster'),
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
    public function destroy(Blog $blog)
    {
        //
    }
}
