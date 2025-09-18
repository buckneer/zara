
@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3>Create address</h3>
                <p class="text-muted mb-0">Add an address for shipment or billing.</p>
            </div>
            <div>
                <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary">Back to addresses</a>
            </div>
        </div>

        <form action="{{ route('addresses.store') }}" method="POST">
            @csrf
            @include('account.addresses._form', ['buttonText' => 'Create address'])
        </form>
    </div>
@endsection
