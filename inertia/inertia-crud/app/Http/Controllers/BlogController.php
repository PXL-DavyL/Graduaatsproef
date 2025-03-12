<?php

namespace App\Http\Controllers;

use App\Models\Blog;
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
        //

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
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }
}
