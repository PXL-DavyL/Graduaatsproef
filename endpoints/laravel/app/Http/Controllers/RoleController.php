<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller {

    public function get_user_roles(Request $request) {
        if(Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'roles' => $user->getRoleNames()
            ]);
        }
        else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
    public function get_user_permissions(Request $request) {
        if(Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'permissions' => $user->permissions
            ]);
        }
        else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}