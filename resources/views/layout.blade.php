<!DOCTYPE html>
<html>
<head>
    <title>Simple Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Raleway:300,400,600);

        body{
            margin: 0;
            font-size: .9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #212529;
            text-align: left;
            background-color: #f5f8fa;
        }
        .navbar-laravel
        {
            box-shadow: 0 2px 4px rgba(0,0,0,.04);
        }
        .navbar-brand , .nav-link, .my-form, .login-form
        {
            font-family: Raleway, sans-serif;
        }
        .my-form
        {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .my-form .row
        {
            margin-left: 0;
            margin-right: 0;
        }
        .login-form
        {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .login-form .row
        {
            margin-left: 0;
            margin-right: 0;
        }
        .buttons{
            margin-bottom: 10px;
        }
        .item{
            border-bottom: 1px #000 dashed;
            margin-top: 8px;
        }
        .block{
            display: block;
        }
        #popupDialog {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        #overlay{
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        #form-post-template{
            display: none;
        }
        #titleInput{
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            font-size: 12px;
        }
        #bodyTextarea{
            width: 100%;
            height:100px;
            padding: 5px;
            font-size: 12px;
            border-radius: 5px;
        }
        button{
            color: #fff;
            background-color: #0b5ed7;
            border-color: #0a58ca;
            padding: .5rem 1rem;
            font-size: 14px;
            border-radius: .3rem;
        }
    </style>
    <script type="text/javascript">
        window.onload = function (){
            document.getElementsByClassName('navbar-toggler')[0].onclick = function(){
                document.getElementById('navbarSupportedContent').classList.toggle('collapse');
                //document.getElementsByClassName('navbar-nav')[0].classList.toggle('collapse');
                //document.getElementsByClassName('navbar-nav')[0].classList.toggle('show-menu');
            }
            if(document.getElementById('navbarDropdown')) {
                document.getElementById('navbarDropdown').onclick = function () {
                    document.getElementById('navbarDropdown').nextElementSibling.classList.toggle('block');
                }
            }
            getAllPosts();
        };
        function getAllPosts(){
            fetch('https://jsonplaceholder.typicode.com/posts/')
                .then(res => res.json())
                .then(posts => {
                    for (let post of posts) {
                        showPost(post);
                    }
                })
                .catch(err => {
                    throw err;
                });
        }
        @guest

        function showPost(post) {
            const postWrapper = document.createElement('div');
            postWrapper.classList.add('item');
            postWrapper.innerHTML = (`
            <h3>${post.title}</h3>
            <p>${post.body}</p>
           `);
            document.getElementById('content-container').append(postWrapper);
        }

        @else

        function showPost(post){
            const postWrapper = document.createElement('div');
            postWrapper.classList.add('item');
            postWrapper.id = 'post-${post.id}';
            postWrapper.innerHTML = (`
            <h3>${post.title}</h3>
            <p>${post.body}</p>
            <div class="buttons">
                <button onclick="editPost(${post.id});">Edit</button>
                <button onclick="deletePost(${post.id});">Delete</button>
            </div>
           `);
            document.getElementById('content-container').append(postWrapper);
        }
        function addPost(){
            let html = document.getElementById('form-post-template').innerHTML;

            popupOpen(html);
        }
        function save(){
            let parser = new DOMParser();
            const template = parser.parseFromString(document.getElementById("popup-content").innerHTML, 'text/html');
            let data = {
                title: template.getElementById("titleInput").getAttribute('value'),
                body: template.getElementById("bodyTextarea").innerHTML,
                userId: {{ Auth::id() }}
            }
            let postId = template.getElementById("idInput").getAttribute('value');
            if(postId == 0){
                //add post
                fetch('https://jsonplaceholder.typicode.com/posts', {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-type': 'application/json; charset=UTF-8',
                    },
                })
                    .then((response) => response.json())
                    .then((json) => showPost(json));
            }else{
                //edit post
                data.id = postId;
                fetch('https://jsonplaceholder.typicode.com/posts/'+postId, {
                    method: 'PUT',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-type': 'application/json; charset=UTF-8',
                    },
                })
                    .then((response) => {
                        response.json();
                        getAllPosts();
                    })
                    .then((json) => console.log(json));
            }
            closeFn();

        }
        function editPost(id){
            let html = document.getElementById('form-post-template').innerHTML;

            fetch('https://jsonplaceholder.typicode.com/posts/'+id)
                .then((response) => response.json())
                .then((json) => {
                    console.log(json);
                    let post = json;
                    //console.log(post.userId == {{ Auth::id() }});
                    //console.log({{ Auth::id() }});
                    if(post.userId == {{ Auth::id() }}) {
                        //const template = document.createElement('div');
                        let parser = new DOMParser();
                        const template = parser.parseFromString(html, 'text/html');
                        template.getElementsByTagName("h1")[0].innerHTML = 'Edit post';
                        template.getElementById("idInput").value = post.id;
                        template.getElementById("titleInput").setAttribute('value', post.title);//.value = post.title;
                        template.getElementById("bodyTextarea").innerHTML = post.body;
                        html = template.body.innerHTML;
                        //alert(html);
                        popupOpen(html);
                    }
                });
        }
        function deletePost(id){
            if(confirm('Are you sure?')){
                fetch('https://jsonplaceholder.typicode.com/posts/'+id, {
                    method: 'DELETE',
                }).then(()=>{
                    document.getElementById('post-'+id).remove();
                });
            }
        }
        function popupOpen(html){
            document.getElementById("overlay").style.display = 'block';
            document.getElementById("popupDialog").style.display = 'block';
            document.getElementById("popup-content").innerHTML = html;
        }
        function closeFn(){
            document.getElementById("overlay").style.display = 'none';
            document.getElementById("popupDialog").style.display = 'none';
            document.getElementById("popup-content").innerHTML = '';
        }
        @endguest
    </script>
</head>
<body>

<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Blog') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">


                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <section class="bg-light py-3 py-md-5">
        <div class="container">

{{--    <main class="py-4">--}}
        @yield('content')
        <h2>All posts</h2>
            @auth
            <button onclick="addPost();">Add new Post</button>
            @endisset
        <div id="content-container"></div>
        @guest
        @else
            <div id="overlay"></div>
            <div id="popupDialog">
                <div id="popup-content"></div>
                <button onclick="closeFn()">Close</button>
            </div>
            <div id="form-post-template">
                <h1>Create new post</h1>
                <input type="hidden" name="id" value="0" id="idInput">
                <input type="text" name="title" value="" id="titleInput" placeholder="Title">
                <textarea name="body" placeholder="Content" id="bodyTextarea"></textarea>
                <button onclick="save()">Save</button>
            </div>
        @endguest
{{--    </main>--}}
        </div>
    </section>
</div>

</body>
</html>
