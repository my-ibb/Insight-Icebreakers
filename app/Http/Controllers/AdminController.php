<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Authファサードを使用
use App\Models\User;
use App\Models\SoupGameQuestion; 
use App\Models\IntroGameQuestion;

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
        $users = User::paginate(5); // 5個ずつのページングを適用
        return view('auth.passwords.admin.dashboard_users', [
            'users' => $users,
        ]);
    }
        
    public function dashboardQuestions()
    {
        $questions = SoupGameQuestion::paginate(5); // 5個ずつのページングを適用
        return view('auth.passwords.admin.dashboard_questions', [
            'questions' => $questions,
        ]);
    }
    public function dashboardSelfIntroductionQuestions()
    {
        $introQuestions = IntroGameQuestion::paginate(5); // 10個ずつのページングを適用
        return view('auth.passwords.admin.dashboard_self_introduction_questions', [
            'introQuestions' => $introQuestions
        ]);
    }
}
