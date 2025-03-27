<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index() {
        return response()->json([
            'blogs' => Blog::with('poster')->get(),
        ]); 
    }

    public function show(Request $request) {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $blog = Blog::find($request->id);
        if(!$blog) {
            return response()->json([
                'errors' => [
                    'id' => ['Blog not found']
                ]
            ], 404);
        }

        return response()->json([
            'blog' => $blog->load('poster'),
        ]);
    }

    public function edit(Request $request) {
        $request->validate([
            'id' => 'required|integer',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $blog = Blog::find($request->id);
        if(!$blog) {
            return response()->json([
                'errors' => [
                    'id' => ['Blog not found']
                ]
            ], 404);
        }

        $user = Auth::user();
        if($blog->poster_id != $user->id) {
            return response()->json([
                'errors' => [
                    'id' => ['You are not the author of this blog']
                ]
            ], 403);
        }

        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->save();

        return response()->json([
            'blog' => $blog->load('poster'),
        ]);
    }
}

