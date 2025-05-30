<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BlogCommentController extends Controller {

    public function store(Blog $blog, Request $request) : RedirectResponse {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comment = new BlogComment();
        $comment->content = $request->content;
        $comment->poster_id = Auth::user()->id;
        $comment->blog_id = $blog->id;
        $comment->save();

        return to_route('blog.show', $blog);
    }

    public function delete(BlogComment $comment) {
        
        if(Auth::user()->id == $comment->poster_id) {
            $comment->delete();
        }
        else {
            return to_route('blog.show', $comment->blog_id)->withErrors(
                ['comment' => 'You do not have permission to delete this comment']);
        }
    }

    public function update(Blog $blog, BlogComment $comment, Request $request) {

        $request->validate([
            'content' => 'required|string'
        ]);

        if(Auth::user()->id == $comment->poster_id) {
            $comment->content = $request->content;
            $comment->save();

            return to_route('blog.show', $blog);
        }
        else {
            return to_route('blog.show', $comment->blog_id)->withErrors(
                ['comment' => 'You do not have permission to update this comment']);
        }

    }
}