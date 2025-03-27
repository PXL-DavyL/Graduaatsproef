<?php
    namespace App\Http\Controllers;

    use App\Models\User;
    use App\Models\Blog;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    
    class AdminBlogController extends Controller
    {
        public function update(Request $request) {
            $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
                'blog' => 'required|integer',
                'author' => 'required|integer',
            ]);

            $blog = Blog::find($request->blog);

            if(!$blog) {
                return response()->json([
                    'message' => 'blog not found'
                ]);
            }

            $blog->title = $request->title;
            $blog->content = $request->content;

            if($request->author != $blog->poster_id) {
                $new_author = User::find($request->author);
                if(!$new_author) {
                    return response()->json([
                        'message' => 'author not found'
                    ], 422);
                }
                
                $blog->poster_id = $request->author;
            }

            $blog->save();
        }

        public function destroy(Request $request) {
            $request->validate([
                'blog' => 'required|integer',
                'title' => 'required|string',
            ]);

            $blog = Blog::find($request->blog);

            if(!$blog) {
                return response()->json([
                    'message' => 'blog not found'
                ], 422);
            }

            if($blog->title != $request->title) {
                return response()->json([
                    'message' => 'title does not match'
                ], 422);
            }

            $blog->delete();
        }
    }