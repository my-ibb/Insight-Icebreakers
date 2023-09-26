{{-- プレイヤー設定画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">プレイヤー設定画面</h1>

    <form method="POST" action="{{ route('selfIntroductionLieGame.start') }}">
        @csrf
        <div id="playerNamesContainer">
            <!-- JavaScriptによりここにプレイヤー名の入力欄が動的に生成される -->
        </div>

        <!-- 参加人数選択 -->
        <div class="form-group">
            <label for="number_of_players">参加人数</label>
            <select class="form-control" id="number_of_players" name="number_of_players">
                <option value="" selected disabled>選択してください</option>
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
</div>

<script>
    document.getElementById('number_of_players').addEventListener('change', function (e) {
        const container = document.getElementById('playerNamesContainer');
        container.innerHTML = ''; 
        const numberOfPlayers = e.target.value;
        
        // ここでplayer_namesの長さがnumberOfPlayersと等しいかチェックし、等しければその値を使用
        const playerNames = @json($player_names) || [];
        for (let i = 0; i < numberOfPlayers; i++) {
            const div = document.createElement('div');
            div.className = 'form-group';

            const label = document.createElement('label');
            label.htmlFor = `player_name${i}`;
            label.textContent = `プレイヤー${i + 1}の名前`;
            console.log('Number of players:', numberOfPlayers);
            console.log('Player names from session:', playerNames);


            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.id = `player_name${i}`;
            input.name = 'player_names[]';
            input.value = playerNames[i] || ''; // 既存のプレイヤー名をセット、存在しなければ空文字列
            
            div.appendChild(label);
            div.appendChild(input);
            container.appendChild(div);
        }
    });
</script>
@endsection
