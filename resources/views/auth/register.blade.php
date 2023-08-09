<form method="post" action="{{ route('register') }}">
    @csrf
    <label for="username">ユーザー名:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="password_confirmation">パスワードの確認:</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>
    <br>
    <input type="submit" value="登録">
</form>