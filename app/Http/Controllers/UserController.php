<?php

// 名前空間と依存関係を定義
namespace App\Http\Controllers;

use Illuminate\Http\Request;

// UserControllerクラスの定義
class UserController extends Controller
{
    // indexメソッド：ユーザーのマイページを表示
    public function index()
    {
        // 'mypage'という名前のビューを返す
        return view('mypage');
    }
}
