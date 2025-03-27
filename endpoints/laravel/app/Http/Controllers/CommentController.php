<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\BlogComment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function update(Request $request) {

        $request->validate([
            'id' => 'required|integer',
            'content' => 'required|string',
        ]);


        $comment = BlogComment::find($request->id);
        if(!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->content = $request->content;
        $comment->save();
    }

    public function destroy(Request $request) {
        $request->validate([
            'id' => 'required|integer',
        ]);


        $comment = BlogComment::find($request->id);
        if(!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $user = Auth::user();

        if($comment->poster_id != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $comment->delete();
    }

    public function store(Request $request) {
        $request->validate([
            'blog_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        $comment = new BlogComment();
        $comment->blog_id = $request->blog_id;
        $comment->content = $request->content;
        $comment->poster_id = Auth::user()->id;
        $comment->save();
    }

}