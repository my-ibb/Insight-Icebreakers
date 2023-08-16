<!-- resources/views/questions/create.blade.php -->
<h1>Create a Question</h1>

<form method="post" action="{{ route('generate-question') }}">
    @csrf

    <div>
        <label for="genre">ジャンル:</label>
        <select name="genre" id="genre">
            <option value="面白い">面白い</option>
            <option value="怖い">怖い</option>
            <option value="ほっこり">ほっこり</option>
            <option value="日常">日常</option>
            <option value="ファンタジー">ファンタジー</option>
        </select>
            </div>

    <div>
        <label for="difficulty">難易度:</label>
        <select name="difficulty" id="difficulty">
            <option value="簡単">簡単</option>
            <option value="普通">普通</option>
            <option value="難しい">難しい</option>
        </select>
            </div>

    <div>
        <button type="submit">Generate Question</button>
    </div>

</form>

<!-- 生成された質問を表示するための領域 -->
@if(session('question'))
    <h2>Generated Question:</h2>
    <div>{{ session('question') }}</div>
@endif
