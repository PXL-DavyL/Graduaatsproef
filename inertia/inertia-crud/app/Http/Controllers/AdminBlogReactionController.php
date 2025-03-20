<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogReaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminBlogReactionController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return Inertia::render('Admin/Reaction/List', [
            'blog' => $blog,
            'reactions' => $blog->reactions()->with('poster')->paginate(10),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog, BlogReaction $reaction)
    {
        return Inertia::render('Admin/Reaction/Edit', [
            'blog' => $blog->load('poster'),
            'reaction' => $reaction->load('poster'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Blog $blog, BlogReaction $reaction, Request $request)
    {
        $request->validate([
            'type' => 'required|in:upvote,downvote',
        ]);

        $reaction->update([
            'type' => $request->type,
        ]);

        return redirect()->route('admin.reactions.show', $blog);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, BlogReaction $reaction, Request $request) : RedirectResponse
    {
        $request->validate([
            'confirm_author' => 'required|string',
        ]);

        $poster = User::find($reaction->poster_id);

        if($poster->name == $request['confirm_author']) {
            $reaction->delete();
            return to_route('admin.reactions.show', [
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
