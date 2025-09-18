@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="auth-wrapper">
        <div class="container">
            <div class="row gx-5 align-items-center">
                <!-- Left image: hidden on small screens -->
                <div class="col-md-6 d-none d-md-block">
                    <div class="auth-left shadow-sm rounded"
                        style="background-image: url('https://picsum.photos/900/1200?grayscale&random=1');">
                    </div>
                </div>

                <!-- Right: form -->
                <div class="col-12 col-md-6">
                    <div class="card auth-card p-4">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="brand text-uppercase">Z A R A - style</div>
                                <h2 class="form-title mt-3">Sign in</h2>
                                <p class="muted-small">Welcome back — sign in to continue shopping</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
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
                                    <label class="form-label small">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control input-plain" required autofocus>
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

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label small" for="remember">Remember me</label>
                                    </div>
                                    <a href="#" class="small">Forgot password?</a>
                                </div>

                                <button type="submit" class="btn btn-dark btn-plain w-100">Login</button>
                            </form>

                            <div class="auth-footer text-center mt-4">
                                <span>New here?</span>
                                <a href="{{ route('register') }}" class="text-dark ms-1">Create an account</a>
                            </div>

                            <!-- optional alternate small image for mobile -->
                            <div class="d-block d-md-none text-center mt-4">
                                <img src="https://picsum.photos/600/400?random=2" alt="look" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /container -->
    </div> <!-- /auth-wrapper -->
@endsection
