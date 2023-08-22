@extends('layouts.app')

@section('title', 'Generated Question Page')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 center-div">
            <div class="card">
                <div class="card-header bg-light text-dark">
                    <h2>Generated Question:</h2>
                </div>
                <div class="card-body">
                    <p>{{ $question }}</p>
                    <a href="{{ route('questions.create') }}" class="btn btn-primary">Back to Create Question</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
