@extends('auth.layout')

@section('title', "Forgot password")

@section('form-content')
    <h2 class="fs-6 fw-normal text-center mb-3">Forgot Password</h2>
    <form method="POST" action="{{ route('password.email') }}">
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
                <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                @enderror
            </div>
            <div class="col-12">
                <div class="d-grid my-3">
                    <button class="btn btn-primary btn-lg" type="submit">{{ __('Send') }}</button>
                </div>
            </div>
            <div class="col-12">
                <p class="m-0 text-secondary text-center">Have an account? <a href="{{ route('login') }}" class="link-primary text-decoration-none">Sign in</a></p>
            </div>
        </div>
    </form>
@endsection
