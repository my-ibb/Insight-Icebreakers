<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Authファサードを追加

class HomeController extends Controller
{
    public function index()
    {
        $isLoggedIn = Auth::check(); // ユーザーがログインしているかどうかをチェック
        return view('home', ['isLoggedIn' => $isLoggedIn]); // ビューにログイン状態を渡す
    }
}
