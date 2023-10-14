<?php

namespace App\Http\Controllers;

use App\Models\IntroGameQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Authファサードを使用
use App\Models\SoupGameQuestion; 
use App\Models\User;
use App\Models\SelfIntroQuestion;

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
            return redirect()->route('admin.dashboard.users');
        }

        // 認証に失敗したら、ログインフォームに戻る
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function dashboardUsers()
    {
        $users = User::all();  // 全てのユーザーを取得    
        return view('auth.passwords.admin.dashboard_users', [
            'users' => $users,
        ]);
    }
    public function dashboardQuestions()
    {
        $questions = SoupGameQuestion::all();    
        return view('auth.passwords.admin.dashboard_questions', [
            'questions' => $questions,
        ]);
    }

    public function dashboardSelfIntroductionQuestions()
    {
        $introQuestions = IntroGameQuestion::all();  // 全ての自己紹介の質問を取得
    
        return view('auth.passwords.admin.dashboard_self_introduction_questions', [
            'introQuestions' => $introQuestions
        ]);
    }

    public function showDashboard() //AdminController.phpに移動
    {
        $questions = SoupGameQuestion::all();
        return view('auth.passwords.admin.dashboard_users', ['questions' => $questions]);
    }    

}
