<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Authファサードを使用
use App\Models\SoupGameQuestion; 

class AdminController extends Controller
{
    public function login()
    {
        return view('auth.passwords.admin.adminlogin');
    }
    
    public function authenticate(Request $request)
    {
        // バリデーション
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 認証チェック
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // 認証に成功したらダッシュボードへリダイレクト
            return redirect()->route('admin.dashboard');
        }

        // 認証に失敗したら、ログインフォームに戻る
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function dashboard()
{
  // すべての質問を取得
    $questions = SoupGameQuestion::all();

  // ビューに質問のデータを渡す
    return view('auth.passwords.admin.dashboard', ['questions' => $questions]);
}
}