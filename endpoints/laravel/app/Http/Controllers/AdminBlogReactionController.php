<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogReaction;
use Illuminate\Http\Request;

class AdminBlogReactionController extends Controller
{
    public function index(Request $request) {

        $request->validate([
            'id' => 'required|integer', // blog
        ]);

        $blog = Blog::find($request->id);
        if(!$blog) {
            return response()->json([
                'errors' => [
                    'id' => ['Blog not found']
                ]
            ], 404);
        }

        $reactions = $blog->reactions()->with('poster')->orderBy('created_at', 'desc')->get();

        return response()->json(['reactions' => $reactions]);
    }

    public function show(Request $request) {
        
        $request->validate([
            'id' => 'required|integer', // reaction
        ]);

        $reaction = BlogReaction::find($request->id);
        if(!$reaction) {
            return response()->json([
                'errors' => [
                    'id' => ['Reaction not found']
                ]
            ], 404);
        }

        return response()->json(['reaction' => $reaction->load('poster')]);
    }
    public function update(Request $request) {
        
        $request->validate([
            'id' => 'required|integer', // reaction
            'type' => 'required|string|in:upvote,downvote'    
        ]);

        $reaction = BlogReaction::find($request->id);
        if(!$reaction) {
            return response()->json([
                'errors' => [
                    'id' => ['Reaction not found']
                ]
            ], 404);
        }

        $reaction->type = $request->type;
        $reaction->save();
    }
    public function destroy(Request $request) {
        
        $request->validate([
            'id' => 'required|integer', // reaction
            'confirm_author' => 'required|string'
        ]);


        $reaction = BlogReaction::find($request->id);
        if(!$reaction) {
            return response()->json([
                'errors' => [
                    'id' => ['Reaction not found']
                ]
            ], 404);
        }

        if($reaction->poster->name !== $request->confirm_author) {
            return response()->json([
                'errors' => [
                    'confirm_author' => ['Author name does not match']
                ]
            ], 403);
        }

        $reaction->delete();
    }
}
