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
    public function index(Request $request)
    {
        $blog = Blog::find($request->id);
        if(!$blog) {
            return response()->json([
                'errors' => [
                    'id' => ['Blog not found']
                ]
            ], 404);
        }
        
        return response()->json([
            'blog' => $blog,
            'comments' => $blog->comments()->with('poster')->orderBy('created_at', 'desc')->get(),
        ]);

    }

    public function show(Request $request) 
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $comment = BlogComment::find($request->id);
        if(!$comment) {
            return response()->json([
                'errors' => [
                    'id' => ['Comment not found']
                ]
            ], 404);
        }

        return response()->json(['comment' => $comment->load('poster')]);
    }

    public function update(Request $request) {
        $request->validate([
            'id' => 'required|integer',
            'content' => 'required|string',
        ]);

        $comment = BlogComment::find($request->id);
        if(!$comment) {
            return response()->json([
                'errors' => [
                    'id' => ['Comment not found']
                ]
            ], 404);
        }

        $comment->content = $request->content;
        $comment->save();

        return response()->json(['comment' => $comment]);
    }

    public function destroy(Request $request) {
        $request->validate([
            'id' => 'required|integer',
            'confirm_author' => 'required|string'
        ]);

        $comment = BlogComment::find($request->id);
        if(!$comment) {
            return response()->json([
                'errors' => [
                    'id' => ['Comment not found']
                ]
            ], 404);
        }

        if($comment->poster->name !== $request->confirm_author) {
            return response()->json([
                'errors' => [
                    'confirm_author' => ['Author name does not match']
                ]
            ], 403);
        }

        $comment->delete();
    }
}