{{-- 自己紹介設定 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <h1 class="text-center">自己紹介設定画面</h1>
    <!-- 現在のプレイヤー名を表示 -->
    <h2 class="text-center">{{ session('current_player_name', 'Player') }}の番です</h2>


    <form method="POST" action="{{ route('selfIntroductionLieGame.storeTruthAndLie') }}">
    @csrf
        <div id="questionsContainer">
            @foreach($questions as $index => $question)
                <div class="form-group">
                    <label for="content{{ $index }}">設問{{ $index + 1 }}: {{ $question->content }}</label>
                    <input type="text" class="form-control" id="content{{ $index }}" name="content[]">
                </div>
                <br>
            @endforeach
        </div>

        <!-- 送信ボタン -->
        <br>
        <button type="submit" class="btn btn-primary">次へ</button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questions = @json($questions); // サーバーから取得した設問を格納
        const numberOfQuestions = {{ session('number_of_questions', 1) }};
        const container = document.getElementById('questionsContainer');
        container.innerHTML = ''; // containerの中身を空にする
    
        for (let i = 0; i < numberOfQuestions; i++) {
            const div = document.createElement('div');
            div.className = 'form-group';
    
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.id = `content${i}`;
            input.name = 'content[]';
    
            const label = document.createElement('label');
            label.htmlFor = `content${i}`;
            label.textContent = `質問${i + 1}: ${questions[i].content}`; // 設問の内容を含むlabelを生成

            const br = document.createElement('br');

    
            div.appendChild(label);
            div.appendChild(input);
            div.appendChild(br);
            container.appendChild(div);
        }
    });

            document.getElementById('submitBtn').addEventListener('click', async function(e) {
            e.preventDefault(); // デフォルトのフォーム送信を防ぐ
            const submitButton = e.target;
            submitButton.disabled = true; // ボタンを無効化

            try {
                // ここで非同期処理またはバリデーションを行うことができます。
                // 例えば、入力値の確認、外部APIへの問い合わせなど。

                // 問題がなければ、フォームを手動で送信
                document.getElementById('truthAndLieForm').submit();
            } catch (error) {
                console.error("Error during form submission: ", error);
                // エラーが発生した場合（例：バリデーションエラー）、ボタンを再度有効にする
                submitButton.disabled = false;
            }
        });
    </script>
    
@endsection
