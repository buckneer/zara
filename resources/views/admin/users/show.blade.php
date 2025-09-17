{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'User #' . $user->id)

@section('content')
<div class="p-6">
	<div class="d-flex justify-content-between align-items-start mb-4">
		<div>
			<h1 class="h4 mb-1">{{ $user->name ?? '—' }}</h1>
			<div class="small text-muted">{{ $user->email }}</div>
		</div>

		<div class="text-end">
			<a href="{{ route('admin.users.edit', $user) ?? '#' }}" class="btn btn-outline-secondary btn-sm me-2">Edit</a>

			<form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
				@csrf
				@method('DELETE')
				<button class="btn btn-danger btn-sm">Delete</button>
			</form>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card mb-3">
				<div class="card-body">
					<h6 class="mb-2">Profile</h6>
					<div class="row">
						<div class="col-md-6">
							<div class="small text-muted">Name</div>
							<div>{{ $user->name ?? '-' }}</div>
						</div>
						<div class="col-md-6">
							<div class="small text-muted">Email</div>
							<div>{{ $user->email }}</div>
						</div>
						<div class="col-md-6 mt-3">
							<div class="small text-muted">Joined</div>
							<div>{{ optional($user->created_at)->toDayDateTimeString() }}</div>
						</div>
						<div class="col-md-6 mt-3">
							<div class="small text-muted">Last login</div>
							<div>{{ $user->last_login_at ?? '—' }}</div>
						</div>
					</div>
				</div>
			</div>

			{{-- Orders list --}}
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<div>
						<strong>Orders</strong>
						<div class="small text-muted">Recent orders by this user</div>
					</div>
					<div>
						<a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">All orders</a>
					</div>
				</div>

				<div class="card-body p-0">
					@if($user->orders && $user->orders->count())
					<div class="table-responsive">
						<table class="table mb-0 align-middle">
							<thead class="table-light small text-muted">
								<tr>
									<th>Order</th>
									<th>Placed</th>
									<th class="text-end">Total</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($user->orders()->latest()->limit(10)->get() as $order)
								<tr>
									<td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->order_number }}</a></td>
									<td class="small text-muted">{{ optional($order->placed_at)->format('Y-m-d') }}</td>
									<td class="text-end">{{ number_format($order->grand_total ?? $order->total ?? 0, 2) }} €</td>
									<td><span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'secondary') }} text-capitalize">{{ $order->status }}</span></td>
									<td class="text-end">
										<a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@else
					<div class="p-3 text-muted small">This user has no orders.</div>
					@endif
				</div>
			</div>
		</div>

		<aside class="col-lg-4">
			<div class="card mb-3">
				<div class="card-body">
					<h6 class="mb-2">Role</h6>

					@php
					use App\Models\Role;
					$roles = Role::orderBy('name')->get();
					@endphp

					<form action="{{ route('admin.users.update', $user) }}" method="POST" id="role-change-form">
						@csrf
						@method('PUT')

						<div class="mb-2">
							<select name="roles[]" class="form-select" onchange="document.getElementById('role-change-form').submit();">
								<option value="">— No role —</option>
								@foreach($roles as $role)
								<option value="{{ $role->id }}" @if($user->roles->contains('id', $role->id)) selected @endif>
									{{ $role->label ?: $role->name }}
								</option>
								@endforeach
							</select>
						</div>

						<noscript><button class="btn btn-primary btn-sm">Save role</button></noscript>
					</form>
				</div>
			</div>

			<div class="card">
				<div class="card-body small text-muted">
					<div><strong>ID</strong> {{ $user->id }}</div>
					<div class="mt-2"><strong>Roles</strong>
						@foreach($user->roles as $r)
						<span class="badge bg-secondary me-1">{{ $r->label ?: $r->name }}</span>
						@endforeach
					</div>
				</div>
			</div>
		</aside>
	</div>
</div>
@endsection