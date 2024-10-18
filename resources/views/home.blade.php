@extends('template')

@section('title', $post ? "Post" : "Home page")

@section('content')
    <div class="home">
        @if (count($posts) > 0)
            <h1>All posts</h1>
            @auth
                <button class="btn btn-primary btn-lg" onclick="window.location.href='{{ route('editPost',['id'=>0]) }}'">Add new Post</button>
            @endisset
            <div id="content-container">
                @foreach ($posts as $post)
                <div class="item">
                    <h2 onclick="window.location.href='{{ route('post',['post_id'=>$post->id]) }}'">{{$post->title}}</h3>
                    <p onclick="window.location.href='{{ route('post',['post_id'=>$post->id]) }}'">{{$post->body}}</p>
                    @auth
                        @if ($post->userId == Auth::id())
                    <div class="buttons">
                        <button class="green" onclick="window.location.href='{{route('editPost',['id'=>$post->id])}}'">Edit</button>
                        <button class="red" onclick="deletePost('{{route('deletePost',['id'=>$post->id])}}');">Delete</button>
                    </div>
                        @endif
                    @endisset
                </div>
                @endforeach
            </div>
        @endif
        @if ($post && count($posts) == 0)
            <h1>{{$post->title}}</h1>
            <p>{{$post->body}}</p>
            @if ($post->userId == Auth::id())
                <div class="buttons">
                    <button class="green" onclick="window.location.href='{{route('editPost',['id'=>$post->id])}}'">Edit</button>
                    <button class="red" onclick="deletePost('{{route('deletePost',['id'=>$post->id])}}')">Delete</button>
                </div>
            @endif
        @endif
    </div>
@endsection
