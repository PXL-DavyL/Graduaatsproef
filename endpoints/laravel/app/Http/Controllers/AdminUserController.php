<?php
    namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    
    class AdminUserController extends Controller
    {
        public function getUser(Request $request) {
            $request->validate([
                'user' => 'required|integer'
            ]);

            $user = User::find($request->user);

            return response()->json([
                'user' => $user
            ]);
        }

        public function saveUser(Request $request) {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'nullable|string',
                'password_confirmation' => 'same:password',
                'user' => 'required|integer'
            ]);

            $user = User::find($request->user);
            if(!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
        }
    }