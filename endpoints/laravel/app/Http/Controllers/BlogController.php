<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index() {
        return response()->json([
            'blogs' => Blog::with('poster', 'comments', 'reactions')->get(),
        ]); 
    }

    public function add_view(Request $request) {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $blog = Blog::find($request->id);
        
        // Pointless to send back an error. The user doesn't care if the views go up or not.
        if($blog) {

            $blog->views++;
            $blog->save();
        }
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
            'blog' => $blog->load('poster', 'comments', 'reactions'),
            'comments' => $blog->comments()->with('poster')->orderBy('created_at', 'desc')->get(),
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

    public function store(Request $request) {

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $author = Auth::user();

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->poster_id = $author->id;
        $blog->save();

        return response()->json([
            'blog' => $blog
        ]);
    }

    public function destroy(Request $request) {
        $request->validate([
            'blog' => 'required|integer',
            'confirm_title' => 'required|string',
        ]);

        $blog = Blog::find($request->blog);
        if(!$blog) {
            return response()->json([
                'errors' => [
                    'blog' => ['Blog not found']
                ]
            ], 404);
        }

        if($blog->title != $request->confirm_title) {
            return response()->json([
                'errors' => [
                    'confirm_title' => ['Title does not match']
                ]
            ], 422);
        }

        $user = Auth::user();
        if($user->id != $blog->poster_id) {
            return response()->json([
                'errors' => [
                    'blog' => ['You are not the author of this blog']
                ]
            ], 403);
        }

        $blog->delete();
    }
}
