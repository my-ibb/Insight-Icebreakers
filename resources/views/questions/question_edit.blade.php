<!-- /Applications/MAMP/htdocs/InsightIcebreakers/resources/views/questions/question_edit.blade.php -->

@extends('layouts.app')

@section('content')
    <br>
    <h2>問題編集</h2>
        
        <!-- エラーメッセージの表示 -->
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('questions.update', $question->id) }}" method="post">
        @csrf
        @method('PUT')
        <br>
        <div class="form-group">
            <label for="question_content">問題:</label>
            <textarea name="question_content" id="question_content" class="form-control">{{ old('question_content', $question->question_content) }}</textarea>
        </div>
        <br>
        <div class="form-group">
            <label for="answer_content">解答:</label>
            <textarea name="answer_content" id="answer_content" class="form-control">{{ old('answer_content', $question->answer_content) }}</textarea>
        </div>
        <br>
        <div class="form-group">
            <label for="genre">ジャンル:</label>
            <select name="genre" id="genre" class="form-control">
                @php
                $genres = ['ビジネス', '学校', '怖い', '恋愛', '日常', '非日常', 'スポーツ', 'サスペンス']; // ジャンルの配列
                @endphp
                @foreach ($genres as $genre)
                <option value="{{ $genre }}" {{ old('genre', $question->genre) === $genre ? 'selected' : '' }}>{{ $genre }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <div class="form-group">
            <label for="difficulty">難易度:</label>
            <select name="difficulty" id="difficulty" class="form-control">
                @php
                $levels = ['簡単', '普通', '難しい']; // 難易度の配列
                @endphp
                @foreach ($levels as $level)
                <option value="{{ $level }}" {{ old('difficulty', $question->difficulty) === $level ? 'selected' : '' }}>{{ $level }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection