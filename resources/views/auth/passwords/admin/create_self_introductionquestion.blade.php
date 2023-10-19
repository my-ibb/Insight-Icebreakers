@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Question</h2>

        <form action="{{ route('admin.questions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="content">Question Content:</label>
                <input type="text" class="form-control" id="content" name="content" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection