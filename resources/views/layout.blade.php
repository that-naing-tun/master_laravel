<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}"> --}}
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
            <h5 class="m-0">Laravel Blog</h5>
            <nav class="my-2 my-md-0">
                <a class="p-2 text-dark" href="{{ route('home') }}">Home</a>
                <a class="p-2 text-dark" href="{{ route('contact') }}">Contact</a>
                <a class="p-2 text-dark" href="{{ route('posts.index') }}">Blog Posts</a>
                <a class="p-2 text-dark" href="{{ route('posts.create') }}">Add</a>
                @guest
                    @if (Route::has('register'))
                        <a class="p-2 text-dark" href="{{ route('register') }}">Register! </a>
                    @endif
                    <a class="p-2 text-dark" href="{{ route('login') }}">Login! </a>
                @else
                    <a class="p-2 text-dark" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout
                        ({{ Auth::user()->name }}) </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                        @csrf
                    </form>
                @endguest
            </nav>
        </div>
    </div>


    {{-- <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
        <li><a href="{{ route('posts.index') }}">Blog Post</a></li>
        <li><a href="{{ route('posts.create') }}"> Add Blog Post</a></li>
    </ul> --}}
    {{-- <div class="container d-flex justify-content-between">
        @if (session('status'))
            <p style="color: green ">
                {{ session('status') }}
            </p>
        @endif

        @yield('content')
    </div> --}}

    <div class="container">
        @if (session('status'))
            <p style="color: green">
                {{ session('status') }}
            </p>
        @endif

        <div class="d-flex justify-content-center">
            <div>
                @yield('content')
            </div>
        </div>
    </div>
    {{-- <script src="{{ asset('assets/js/script.js') }}"></script> --}}
</body>

</html>
