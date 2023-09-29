{{-- 自己紹介設定 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">自己紹介設定画面</h1>

    <form method="POST" action="{{ route('selfIntroductionLieGame.completeQuestion') }}">
    @csrf
        <div id="questionsContainer">
            @foreach($questions as $index => $question)
                <div class="form-group">
                    <label for="content{{ $index }}">設問{{ $index + 1 }}: {{ $question->content }}</label>
                    <input type="text" class="form-control" id="content{{ $index }}" name="content[]">
                </div>
            @endforeach
        </div>

        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary">次へ</button>
    </form>
</div>
<script>
    // ドキュメントが完全に読み込まれたら、このコードブロックが実行されます
    document.addEventListener('DOMContentLoaded', function () {
        // セッションから設問数を取得し、JavaScript変数に代入します。
        // セッションに設問数がない場合、1が代入されます。
        const numberOfQuestions = {{ session('number_of_questions', 1) }};
        // 設問の入力フィールドを格納するコンテナを取得します。
        const container = document.getElementById('questionsContainer');
        // 設問数分の入力フィールドを生成します。
        for (let i = 0; i < numberOfQuestions; i++) {
            // 新しいdiv要素（入力フィールドのコンテナ）を作成します。
            const div = document.createElement('div');
            div.className = 'form-group';// Bootstrapのクラス名

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';// Bootstrapのクラス名
            input.id = `content${i}`;
            input.name = 'content[]'; // PHP側で配列として受け取るための記述

            // 作成したlabelとinputをdivに追加します。
            div.appendChild(label);
            div.appendChild(input);
            // 作成したdivをコンテナに追加します。
            container.appendChild(div);
        }
    });
</script>

@endsection
