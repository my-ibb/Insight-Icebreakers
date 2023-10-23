<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- 文字コードの設定 -->
    <meta charset="UTF-8">
    <!-- ビューポートの設定 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BootstrapのCSSをCDNから読み込み -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- カスタムCSSの読み込み -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon_turtle.png') }}"> 

    {{-- Googleフォント --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
    
    <script src="{{ asset('/js/toggleAnswer.js') }}">
    </script>

</head>
<body class="bg-light-cyan"> <!-- 背景色を設定するカスタムクラス -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #387cd0;">
            <div class="container-fluid">
                <!-- ナビゲーションバーブランド（ロゴやサイト名） -->
                <a class="navbar-brand" href="{{ route('home') }}">Insight Icebreaker</a> <!-- サイト名またはロゴへのパスを指定 -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- 右側のナビゲーションメニュー -->
                    <ul class="navbar-nav ms-auto">
                        @if (Auth::check())
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
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">新規登録</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>    
    </header>

    <main>
        <!-- この部分が各ビューファイルから埋められる -->
        @yield('content')
    </main>

    <footer>
        <!-- フッターのコンテンツをここに追加 -->
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

<style>
    /* フォントの種類入れた */
body { 
    font-family: 'Zen Maru Gothic', serif;
}

</style>