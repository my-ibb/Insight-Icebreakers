<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Question</title>
</head>
<body>
    <form action="{{ route('generate-question') }}" method="POST">
        @csrf <!-- CSRFトークンを追加します。これはLaravelのセキュリティ機能の一部です -->

        <!-- ジャンルや難易度などの追加フィールドがあればここに追加 -->
        <input type="submit" value="Generate Question">
    </form>
</body>
</html>
