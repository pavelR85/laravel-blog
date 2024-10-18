@extends('auth.layout')

@section('title', $post ? "Edit Post" : "Add new Post")

@section('form-content')
    <h2 class="fs-6 fw-normal text-center mb-3">{{$post ? "Edit Post" : "Add new Post"}}</h2>
    <form method="POST" action="{{ route('save.post') }}">
        @csrf

        @session('error')
        <div class="alert alert-danger" role="alert">
            {{ $value }}
        </div>
        @endsession
        <input type="hidden" name="id" value="{{$post?$post->id:0}}">
        <input type="hidden" name="userId" value="{{$post?$post->userId:Auth::id()}}">
        <div class="row gy-2 overflow-hidden">
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input value="{{$post?$post->title:''}}" type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Title of Post" required>
                    <label for="title" class="form-label">{{ __('Title') }}</label>
                </div>
                @error('title')
                <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                @enderror
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="body" placeholder="Text of Post" required>{{$post?$post->body:''}}</textarea>
                    <label for="body" class="form-label">{{ __('Content') }}</label>
                </div>
                @error('body')
                <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                @enderror
            </div>
            <div class="col-12">
                <div class="d-grid my-3">
                    <button class="btn btn-primary btn-lg" type="submit">{{ __('Save') }}</button>
                </div>
            </div>
{{--            <div class="col-12">--}}
{{--                <p class="m-0 text-secondary text-center">Have an account? <a href="{{ route('login') }}" class="link-primary text-decoration-none">Sign in</a></p>--}}
{{--            </div>--}}
        </div>
    </form>
@endsection
