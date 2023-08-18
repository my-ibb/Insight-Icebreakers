<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<h1>Welcome to the Games!</h1>
<div>
    <h2>Select a Game</h2>
    <a href="{{ route('questions.index') }}" class="btn btn-primary">ウミガメのスープ</a><br>
    <a href="#" class="btn btn-secondary mt-2">自己紹介嘘当てゲーム (Coming Soon)</a>

    @if($isLoggedIn)
    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit">Logout</button>
    </form>
    @endif
</div>
@endsection
