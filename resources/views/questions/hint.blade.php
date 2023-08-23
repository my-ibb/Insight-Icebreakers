@extends('layouts.app')

@section('title', 'Hint Page')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Get a Hint</h1>
    <form method="post" action="{{ route('generate-hint') }}">
        @csrf
        <div class="form-group">
            <label for="questionID">Question ID:</label>
            <input type="text" name="questionID" id="questionID" class="form-control">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Get Hint</button>
        </div>
    </form>

    @if(session('hint'))
        <h2>Hint:</h2>
        <div class="alert alert-info">{{ session('hint') }}</div>
    @endif
</div>
@endsection
