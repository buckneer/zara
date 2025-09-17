{{-- resources/views/account/profile.blade.php --}}
@extends('layouts.guest')

@section('content')
<div class="container py-4">
	<div class="row">
		<div class="col-md-8">
			<h3>Account</h3>
			<p class="text-muted">Basic account information.</p>

			<div class="card mb-3">
				<div class="card-body">
					<h5 class="mb-1">{{ Auth::user()->name ?? '—' }}</h5>
					<p class="mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
					<p class="mb-0 text-muted"><small>Member since {{ Auth::user()->created_at ? Auth::user()->created_at->toFormattedDateString() : '—' }}</small></p>
				</div>
			</div>

			<a href="{{ route('addresses.index') }}" class="btn btn-primary">Manage addresses</a>
		</div>
	</div>
</div>
@endsection