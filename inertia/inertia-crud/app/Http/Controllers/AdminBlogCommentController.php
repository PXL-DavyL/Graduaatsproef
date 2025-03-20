<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminBlogCommentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return Inertia::render('Admin/Comment/List', [
            'blog' => $blog,
            'comments' => $blog->comments()->with('poster')->paginate(10),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog, BlogComment $comment)
    {
        return Inertia::render('Admin/Comment/Edit', [
            'blog' => $blog->load('poster'),
            'comment' => $comment->load('poster'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Blog $blog, BlogComment $comment, Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->content = $request->content;
        $comment->save();

        return to_route('admin.comments.edit', [
            'blog' => $blog, 
            'comment' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, BlogComment $comment, Request $request) : RedirectResponse
    {
        $request->validate([
            'confirm_author' => 'required|string',
        ]);

        $poster = User::find($comment->poster_id);

        if($poster->name == $request['confirm_author']) {
            $comment->delete();
            return to_route('admin.comments.show', [
                'blog' => $blog
            ]);
        }
        else {
            return back()->withErrors([
                'confirm_author' => 'Author name does not match the comment poster'
            ]);
        }
    }
}
