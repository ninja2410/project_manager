@extends('layouts/default')

@section('title',"Bienvenido")
@section('page_parent',"Home")

@section('content')
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ url('/auth/login') }}">Login</a>
                <a href="#">Register</a>
            @endauth
        </div>
    @endif

    <div class="content">
        <h1>
            Cacao.gt
        </h1>

        
    </div>
</div>   
@endsection