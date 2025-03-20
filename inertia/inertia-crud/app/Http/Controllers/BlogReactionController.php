<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BlogReactionController extends Controller {

    public function toggle_reaction(Blog $blog, Request $request) : RedirectResponse {

        $request->validate([
            'type' => 'required|string|in:upvote,downvote'
        ]);

        $reaction = $blog->reactions()->where('poster_id', Auth::id())->first();

        if($reaction) {
            if($reaction->type == $request->type) {
                $reaction->delete();
            }
            else {
                $reaction->type = $request->type;
                $reaction->save();
            }
        }
        else {
            $reaction = $blog->reactions()->create([
                'type' => $request->type,
                'poster_id' => Auth::id()
            ]);
        }

        return to_route('blog.show', $blog);
    }
}