<!-- resources/views/auth/login.blade.php -->
<h1>Login Form</h1>

<!-- ここにログインフォームのHTMLを記述します -->
<form method="post" action="/login">
    @csrf

    <label for="username">ユーザー名またはメールアドレス:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <input type="submit" value="ログイン">
</form>  
<p>
    <a href="{{ url('/register') }}">新規登録</a>
</p>
