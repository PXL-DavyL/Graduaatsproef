<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AdminPermissionController extends Controller {

    public function index() {
        return response()->json(['permissions' => Permission::all()]);
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'permission' => 'required'
        ]);

        if($user->hasPermissionTo($request->permission['name'])) {
            $user->revokePermissionTo($request->permission['name']);
        }
        else $user->givePermissionTo($request->permission['name']);
    }

    public function toggle_admin(User $user) {
        if($user->hasRole('admin')) {
            $user->removeRole('admin');
        }

        else $user->assignRole('admin');
    }
}