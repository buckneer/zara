@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="admin-ui">
    <div class="p-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h4 mb-0 admin-page-title">Users</h1>
                <small class="text-muted">Manage customer accounts</small>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card admin-card">
            <div class="table-responsive">
                <table class="table admin-table mb-0 align-middle">
                    <thead class="admin-thead table-light small text-muted">
                        <tr>
                            <th class="admin-th">#</th>
                            <th class="admin-th">Name / Email</th>
                            <th class="admin-th">Joined</th>
                            <th class="admin-th">Role</th>
                            <th class="admin-th admin-th-actions text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            use App\Models\Role;
                            $roles = Role::orderBy('name')->get();
                        @endphp

                        @forelse($users as $user)
                            <tr>
                                <td class="admin-td">{{ $user->id }}</td>
                                <td class="admin-td">
                                    <div class="fw-semibold admin-item-title">{{ $user->name ?? '-' }}</div>
                                    <div class="small text-muted">{{ $user->email }}</div>
                                </td>
                                <td class="admin-td small text-muted">{{ optional($user->created_at)->format('Y-m-d') }}</td>

                                <td class="admin-td" style="min-width:220px;">
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST"
                                        class="d-flex align-items-center" id="role-form-{{ $user->id }}">
                                        @csrf
                                        @method('PUT')

                                        <select name="roles[]" class="form-select form-select-sm me-2"
                                            onchange="document.getElementById('role-form-{{ $user->id }}').submit();">
                                            <option value="">— No role —</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    @if ($user->roles->contains('id', $role->id)) selected @endif>
                                                    {{ $role->label ?: $role->name }}</option>
                                            @endforeach
                                        </select>

                                        <noscript>
                                            <button class="btn btn-sm btn-primary">Save</button>
                                        </noscript>
                                    </form>
                                </td>

                                <td class="admin-td text-end">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="btn btn-sm btn-outline-primary me-1 admin-action">View</a>
                                    <a href="{{ route('admin.users.edit', $user) ?? '#' }}"
                                        class="btn btn-sm btn-outline-secondary me-1 admin-action">Edit</a>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this user? This will remove their account and cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger admin-action">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer admin-card-footer d-flex justify-content-between align-items-center">
                <div class="small text-muted">Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of
                    {{ $users->total() ?? 0 }}</div>
                <div>
                    @if (method_exists($users, 'links'))
                        {{ $users->withQueryString()->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
