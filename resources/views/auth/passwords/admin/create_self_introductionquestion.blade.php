@extends('layouts.app')

@section('content')
    <div class="container">
        <br>
        <h2>自己紹介設問作成</h2>

        <form action="{{ route('admin.questions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="content">設問:</label>
                <br>
                <input type="text" class="form-control" id="content" name="content" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">作成する</button>
        </form>
    </div>
@endsection