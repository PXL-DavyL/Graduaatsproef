<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller {

    public function get_all_permissions() {
        return response()->json(['permissions' => Permission::all()]);   
    }
    

    public function get_auth_roles(Request $request) {
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

    public function get_auth_permissions(Request $request) {
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

    public function get_user_permissions(Request $request) {

        $request->validate([
            'user' => 'required|integer'
        ]);

        $user = User::find($request->user);
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'permissions' => $user->permissions
        ]);
    }

    public function get_user_roles(Request $request) {

        $request->validate([
            'user' => 'required|integer'
        ]);

        $user = User::find($request->user);
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'roles' => $user->getRoleNames()
        ]);
    }

    public function save_user_permissions(Request $request) {
        if(Auth::check()) {
            if(!Auth::user()->hasRole('admin')) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }
        else return response()->json(['message' => 'Unauthorized'], 401);

        $request->validate([
            'permission' => 'required',
            'user' => 'required|integer'
        ]);

        $user = User::find($request->user);
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if($user->hasPermissionTo($request->permission['name'])) {
            $user->revokePermissionTo($request->permission['name']);
        }
        else {
            $user->givePermissionTo($request->permission['name']);
        }
    }

    public function toggle_admin_role(Request $request) {

        if(Auth::check()) {
            if(!Auth::user()->hasRole('admin')) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }
        else return response()->json(['message' => 'Unauthorized'], 401);

        $request->validate([
            'user' => 'required|integer'
        ]);

        $user = User::find($request->user);
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if($user->hasRole('admin')) {
            $user->removeRole('admin');
        }
        else {
            $user->assignRole('admin');
        }
    }
}