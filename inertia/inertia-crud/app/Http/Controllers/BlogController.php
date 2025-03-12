<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'title' => 'required',
            'content' => 'required',
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
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
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
        //
        $request->validate([
            'title' => 'nullable',
            'content' => 'nullable',
            'author_id' => 'nullable',
        ]);

        if($request->title != null) {
            $blog->title = $request->title;
        }

        if($request->content != null) {
            $blog->content = $request->content;
        }

        if($request->author != null) {
            $blog->poster_id = $request->author['id'];
        }

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
