{{-- プレイヤー設定画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">プレイヤー設定画面</h1>

    <form method="POST" action="{{ route('selfIntroductionLieGame.start') }}">
    @csrf
        <div id="playerNamesContainer"></div>

        <!-- 参加人数選択 -->
        <div class="form-group">
            <label for="number_of_players">参加人数</label>
            <select class="form-control" id="number_of_players" name="number_of_players">
                @foreach ($numberOfPlayersOptions as $option)
                    <option value="{{ $option }}">{{ $option }}人</option>
                @endforeach
            </select>
        </div>

        <!-- 設問数選択 -->
        <div class="form-group">
            <label for="number_of_questions">設問数</label>
            <select class="form-control" id="number_of_questions" name="number_of_questions">
                @foreach ($numberOfQuestionsOptions as $option)
                    <option value="{{ $option }}">{{ $option }}問</option>
                @endforeach
            </select>
        </div>

        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary">開始</button>
    </form>
    <div class="form-group" id="player-names-container">
        {{-- ここにJavaScriptでプレイヤー名の入力欄を動的に生成する --}}

</div>

<script>
    // 参加人数のセレクトボックスの値が変更されたときに実行されるイベントリスナーを追加します。
    document.getElementById('number_of_players').addEventListener('change', function (e) {

        // プレイヤー名の入力フィールドを格納するコンテナを取得します。
        const container = document.getElementById('playerNamesContainer');
        // コンテナの内容をリセットします（前回の入力フィールドを削除します）。
        container.innerHTML = ''; 
        // 選択された参加人数を取得します。
        const numberOfPlayers = e.target.value
        // 参加人数分の入力フィールドを生成します。;
        for (let i = 0; i < numberOfPlayers; i++) {
            // 新しいdiv要素（入力フィールドのコンテナ）を作成します。
            const div = document.createElement('div');
            div.className = 'form-group';// Bootstrapのクラス名

            // label要素を作成し、for属性とテキストコンテンツを設定します。
            const label = document.createElement('label');
            label.for = `player_name${i}`;
            label.textContent = `プレイヤー${i + 1}の名前`;

            // input要素を作成し、type, class, id, name属性を設定します。
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.id = `player_name${i}`;
            input.name = 'player_names[]'; // 配列形式のname属性

            // 作成したlabelとinputをdivに追加します。
            div.appendChild(label);
            div.appendChild(input);

             // 作成したdivをコンテナに追加します。
            container.appendChild(div);
        }
    });
</script>
@endsection
