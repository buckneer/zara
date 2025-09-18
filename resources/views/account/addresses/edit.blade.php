{{-- resources/views/account/addresses/edit.blade.php --}}
@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3>Edit address</h3>
                <p class="text-muted mb-0">Update your address details.</p>
            </div>
            <div>
                <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary">Back to addresses</a>
            </div>
        </div>

        <form action="{{ route('addresses.update', $address) }}" method="POST">
            @csrf
            @method('PUT')
            @include('account.addresses._form', ['buttonText' => 'Save changes'])
        </form>

        <div class="mt-3">
            <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                onsubmit="return confirm('Delete this address? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete address</button>
            </form>
        </div>
    </div>
@endsection
