<!-- /Applications/MAMP/htdocs/InsightIcebreakers/resources/views/questions/question_edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h2>Question Edit</h2>

    <form action="{{ route('questions.update', $question->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="question_content">Question Content:</label>
            <textarea name="question_content" id="question_content" class="form-control">{{ old('question_content', $question->question_content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="answer_content">Answer Content:</label>
            <textarea name="answer_content" id="answer_content" class="form-control">{{ old('answer_content', $question->answer_content) }}</textarea>
        </div>

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

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection