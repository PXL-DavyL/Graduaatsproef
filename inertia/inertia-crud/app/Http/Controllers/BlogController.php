<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : RedirectResponse
    {
        return to_route('home'); // our home page is this index
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Blog/Create');
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
        return Inertia::render('Blog/Show', [
            'blog' => $blog->load('poster'),
            'comments' => $blog->comments()->with('poster')->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        if(!Auth::user()->hasRole('admin')) {
            if(Auth::id() != $blog->poster_id) {
                return to_route('home')->withErrors(['blog_author_error' => 'You are not the author of this blog.']);
            }
        }

        return Inertia::render('Blog/Edit', [
            'blog' => $blog->load('poster'),
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        if(Auth::id() != $blog->poster_id) {
            return to_route('home')->withErrors(['blog_author_error' => 'You are not the author of this blog.']);
        }

        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:5'
        ]);

        $blog->title = $request->title;
        $blog->content = $request->content;

        $blog->save();

        return to_route('blog.edit', $blog);
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

        return to_route('home');
    }
}
