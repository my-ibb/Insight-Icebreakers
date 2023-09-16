@extends('layouts.app')

@section('content')
    <h1>ランキング</h1>
    
    <h2>{{ $question->title ?? '指定の問題' }}</h2>
    
    <table>
        <thead>
            <tr>
                <th>ユーザー名</th>
                <th>スコア</th>
                <th>ヒント使用回数</th>
                <th>質問回数</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scores as $score)
            <tr>
                <td>{{ $score->user->name }}</td>
                <td>{{ $score->score }}</td>
                <td>{{ $score->hint_count }}</td>
                <td>{{ $score->question_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
