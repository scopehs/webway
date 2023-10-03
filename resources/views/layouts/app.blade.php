<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{!! asset('svg/logo.svg') !!}" />

    <!-- Scripts -->

    <script>
        //
        window.onload = function() {
            axios.get('/sanctum/csrf-cookie')
            // {withCredentials: true}
        }
    </script>


    <!-- Fonts -->
     <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@300;400;700&display=swap" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    {{-- <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet"> --}}
    <script>
        @auth
            window.Laravel = {
            jsPermissions: {!! auth()->user()?->getCheckPermissions() !!},

            }
        @else
            window.Permissions = [];
        @endauth
    </script>
@if(Auth::check() or Auth::viaRemember())
@vite(['resources/js/app.js'])
@vite(['resources/sass/app.scss'])
@endif

    <!-- Styles -->

</head>

<body style="background-color:#202020;">

    <div id="app">
        @yield('content')
    </div>
</body>

</html>
