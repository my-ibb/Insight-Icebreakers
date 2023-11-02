<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ページ</title>
    <!-- BootstrapのCSSをCDNから読み込み -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- 他の必要なCSSファイルをここにリンクする -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #387cd0;">
            <div class="container-fluid">
                <!-- ナビゲーションバーブランド（ロゴやサイト名） -->
                <a class="navbar-brand" href="{{ route('admin.dashboard.users') }}">管理者ページ</a> <!-- サイト名またはロゴへのパスを指定 -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- 右側のナビゲーションメニュー -->
                    <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <span class="navbar-text me-3">
                                    {{ Auth::user()->username }} さん、こんにちは！
                                </span>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">ログアウト</button>
                                </form>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>    
    </header>
    <main class="container mt-4">
        @yield('content') <!-- ここで各ページのコンテンツが表示されます -->
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- 他の必要なJavaScriptファイルをここにリンクする -->
</body>
</html>
