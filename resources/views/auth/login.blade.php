@extends('auth.layout')

@section('title', "User Login Page")

@section('form-content')
    <h1 class="fs-6 fw-normal text-center text-secondary mb-4">Sign in to your account</h1>
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        @session('error')
        <div class="alert alert-danger" role="alert">
            {{ $value }}
        </div>
        @endsession

        <div class="row gy-2 overflow-hidden">
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="name@example.com" required>
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
  </span>
                @enderror
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" value="" placeholder="Password" required>
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                </div>
                @error('password')
                <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
  </span>
                @enderror
            </div>
            <div class="col-12">
                <div class="d-flex gap-2 justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" name="rememberMe" id="rememberMe">
                        <label class="form-check-label text-secondary" for="rememberMe">
                            Keep me logged in
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="link-primary text-decoration-none">{{ __('forgot password?') }}</a>
                </div>
            </div>
            <div class="col-12">
                <div class="d-grid my-3">
                    <button class="btn btn-primary btn-lg" type="submit">{{ __('Login') }}</button>
                </div>
            </div>
            <div class="col-12">
                <p class="m-0 text-secondary text-center">Don't have an account? <a href="{{ route('register') }}" class="link-primary text-decoration-none">Sign up</a></p>
            </div>
        </div>
    </form>
@endsection
