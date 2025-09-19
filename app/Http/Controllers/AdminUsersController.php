<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount('orders')->orderByDesc('created_at');

        if ($q = $request->input('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ]);

        DB::transaction(function () use ($data) {
            $user = new User();
            $user->name = $data['name'] ?? null;
            $user->email = $data['email'];
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();

            if (!empty($data['roles'])) {
                $user->roles()->sync($data['roles']);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function show(User $user)
    {
        $user->load(['orders' => function ($q) {
            $q->latest()->limit(50);
        }, 'roles']);
        $roles = Role::orderBy('name')->get();

        return view('admin.users.show', compact('user', 'roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // If request is only changing roles (from the users index role dropdown)
        if ($request->has('roles') && !$request->has('email') && !$request->has('name') && !$request->has('password')) {
            $data = $request->validate([
                'roles' => 'nullable|array',
                'roles.*' => 'nullable|integer|exists:roles,id',
            ]);

            // Normalize: remove empty strings
            $roles = array_filter($data['roles'] ?? [], function ($r) {
                return $r !== '' && $r !== null;
            });

            // Prevent removing admin role if it would leave zero admins
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $isRemovingAdminFromThisUser = $user->roles()->where('role_id', $adminRole->id)->exists()
                    && !in_array($adminRole->id, $roles);

                if ($isRemovingAdminFromThisUser) {
                    $adminCount = $adminRole->users()->count();
                    if ($adminCount <= 1) {
                        return redirect()->back()->with('error', 'Cannot remove the admin role — there must be at least one admin.');
                    }
                }
            }

            DB::transaction(function () use ($user, $roles) {
                $user->roles()->sync(array_values($roles));
            });

            return redirect()->route('admin.users.index')->with('success', 'Roles updated.');
        }

        // Full user update path (existing behaviour)
        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ]);

        DB::transaction(function () use ($data, $user) {
            $user->name = $data['name'] ?? null;
            $user->email = $data['email'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            $user->roles()->sync($data['roles'] ?? []);
        });

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $me = Auth::user();
        if ($me && $me->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account while logged in.');
        }


        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && $user->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminCount = $adminRole->users()->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Cannot delete the last admin user.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }


    public function assignRole(Request $request, Role $role)
    {
        $data = $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($data['user_id']);
        $user->roles()->syncWithoutDetaching([$role->id]);
        return redirect()->back()->with('success', 'Role assigned.');
    }



    public function revokeRole(Request $request, Role $role)
    {
        $data = $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($data['user_id']);


        if ($role->name === 'admin') {
            $adminCount = $role->users()->count();
            if ($adminCount <= 1 && $user->roles()->where('role_id', $role->id)->exists()) {
                return redirect()->back()->with('error', 'Cannot revoke admin role from the last admin.');
            }
        }

        $user->roles()->detach($role->id);
        return redirect()->back()->with('success', 'Role revoked.');
    }
}
