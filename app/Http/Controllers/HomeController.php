<?php
// 名前空間定義: このクラスがApp\Http\Controllersという名前空間に属する
namespace App\Http\Controllers;

// ユーザーからのHTTPリクエストをハンドリングするためのクラス
// 今回は使用されていない
use Illuminate\Http\Request;

// Auth（認証）に関連するクラス。ユーザーがログインしているかどうかを確認するのに使う
use Illuminate\Support\Facades\Auth;

// Controllerクラスを継承して新しいコントローラを作成
class HomeController extends Controller
{
    // indexメソッド: ユーザーがホームページにアクセスしたときに呼ばれる
    public function index()
    {
        // Auth::check()でログイン状態を確認。trueならログイン中、falseなら非ログイン
        $isLoggedIn = Auth::check();

        // ログイン状態の情報をビュー（home.blade.php）に渡す
        return view('home', ['isLoggedIn' => $isLoggedIn]);
    }
}
