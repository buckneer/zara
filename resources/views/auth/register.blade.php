@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <div class="auth-wrapper">
        <div class="container">
            <div class="row gx-5 align-items-center">
                
                <div class="col-md-6 d-none d-md-block">
                    <div class="auth-left shadow-sm rounded"
                        style="background-image: url('https://picsum.photos/900/1200?random=3');">
                    </div>
                </div>

                
                <div class="col-12 col-md-6">
                    <div class="card auth-card p-4">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="brand text-uppercase">Z A R A - style</div>
                                <h2 class="form-title mt-3">Create account</h2>
                                <p class="muted-small">Sign up to get access to exclusive pieces</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                @if ($errors->any())
                                    <div class="alert alert-danger small p-2">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label small">Full name</label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control input-plain" required>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control input-plain" required>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Password</label>
                                    <input type="password" name="password" class="form-control input-plain" required>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Confirm password</label>
                                    <input type="password" name="password_confirmation" class="form-control input-plain"
                                        required>
                                </div>

                                <button type="submit" class="btn btn-dark btn-plain w-100">Create account</button>
                            </form>

                            <p class="text-center muted-small mt-3">
                                Already registered?
                                <a href="{{ route('login') }}" class="text-dark ms-1">Sign in</a>
                            </p>

                            <div class="d-block d-md-none text-center mt-4">
                                <img src="https://picsum.photos/600/400?random=4" alt="look" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div> 
@endsection
