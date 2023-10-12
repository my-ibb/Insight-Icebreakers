<?php

// 名前空間と依存関係を定義
namespace App\Http\Controllers;

use Illuminate\Http\Request;

// UserControllerクラスの定義
class UserController extends Controller
{
    public function index()
    {
        return view('mypage');
    }

    // 新しいメソッドをここに追加
    public function create()
    {
        // 'user_create'という名前のビューを返す
        return view('user_create');
    }
}