<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // ログインフォームのビュー
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/'); // ログイン成功時のリダイレクト先
        }

        return back()->withErrors(['username' => '認証に失敗しました。']); // ログイン失敗時のエラーメッセージ
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/'); // ログアウト後のリダイレクト先
    }
}
