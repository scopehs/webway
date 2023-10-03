@extends('layouts.app')

@section('content')
    @if (Auth::check() or Auth::viaRemember())
    @php
        $a = App\Http\Controllers\UserController::logingUpdate();

    @endphp
        <app :username="{{ json_encode(Auth::user()->name) }}" :user_id="{{ json_encode(Auth::user()->id) }}"
            :char="{{ json_encode(Auth::user()->character_id) }}">
        </app>
    @else
        <script>
            window.location = "/login";
        </script>
    @endif
@endsection
