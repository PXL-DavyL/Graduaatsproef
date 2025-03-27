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

}