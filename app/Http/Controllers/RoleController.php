<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();
        return request()->wantsJson() ? response()->json($roles) : view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'label' => 'nullable|string|max:191',
        ]);

        $role = Role::create($data);
        return $request->wantsJson() ? response()->json($role,201) : redirect()->back()->with('success','Role created.');
    }

    public function assign(Request $request, Role $role)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($data['user_id']);
        $user->roles()->syncWithoutDetaching([$role->id]);

        return $request->wantsJson() ? response()->json(['message'=>'assigned']) : redirect()->back()->with('success','Role assigned.');
    }

    public function revoke(Request $request, Role $role)
    {
        $data = $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($data['user_id']);
        $user->roles()->detach($role->id);

        return $request->wantsJson() ? response()->json(['message'=>'revoked']) : redirect()->back()->with('success','Role revoked.');
    }
}
