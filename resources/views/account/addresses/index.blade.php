{{-- resources/views/account/addresses/index.blade.php --}}
@extends('layouts.guest')

@section('content')
<div class="container py-4">
  <div class="row mb-3">
    <div class="col-md-8">
      <h3>Your addresses</h3>
      <p class="text-muted">Manage shipping/billing addresses for your account.</p>
    </div>
    <div class="col-md-4 text-end">
      <a href="{{ route('addresses.create') }}" class="btn btn-success">+ New address</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($addresses->count() === 0)
    <div class="alert alert-info">You don't have any saved addresses yet. <a href="{{ route('addresses.create') }}">Create one</a>.</div>
  @endif

  <div class="row gy-3">
    @foreach($addresses as $address)
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-1">
              {{ $address->name ? e($address->name) : '—' }}
            </h5>
            @if($address->company)
              <div class="text-muted mb-1">{{ e($address->company) }}</div>
            @endif

            <address class="mb-2 small">
              {{ e($address->line1) }}<br>
              @if($address->line2){{ e($address->line2) }}<br>@endif
              {{ e($address->city) }}@if($address->state), {{ e($address->state) }}@endif<br>
              @if($address->postal_code){{ e($address->postal_code) }}<br>@endif
              {{ e($address->country) }}<br>
              @if($address->phone)<strong>Phone:</strong> {{ e($address->phone) }}@endif
            </address>

            @if($address->notes)
              <p class="small text-muted">Notes: {{ e($address->notes) }}</p>
            @endif
          </div>

          <div class="card-footer bg-transparent d-flex justify-content-between">
            <div>
              <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            </div>

            <div>
              <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this address? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-4">
    @if(method_exists($addresses, 'links'))
      {{ $addresses->withQueryString()->links('pagination::bootstrap-4') }}
    @endif
  </div>
</div>
@endsection
