<?php
    namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    
    class AdminUserController extends Controller
    {
        public function index() {
            return response()->json([
                'users' => User::all(),
            ]); 
        }

        public function show(Request $request) {
            $request->validate([
                'user' => 'required|integer'
            ]);

            $user = User::find($request->user);

            return response()->json([
                'user' => $user
            ]);
        }

        public function update(Request $request) {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'nullable|string',
                'password_confirmation' => 'same:password',
                'user' => 'required|integer'
            ]);

            $user = User::find($request->user);
            if(!$user) {
                return response()->json(['message' => 'User not found'], 422);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
        }

        public function destroy(Request $request) {
            $request->validate([
                'user' => 'required|integer',
                'confirm_password' => 'required',
            ]);
    
            $user = User::find($request->user);
            if(!$user) {
                return response()->json(['user' => 'User not found'], 422);
            }

            $adminUser = Auth::user();
            if(!Hash::check($request->confirm_password, $adminUser->password)) {
                return response()->json([
                    'errors' => [
                        'confirm_password' => ['The password you entered is invalid.']
                    ]
                ], 422);
            }
    
            $user->delete();
        }

        public function store(Request $request) {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'password_confirmation' => 'required|same:password',
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json([
                'user' => $user
            ]);
        }
    }