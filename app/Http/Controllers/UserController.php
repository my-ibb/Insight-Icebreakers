<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

// UserControllerクラスの定義
class UserController extends Controller
{
    // 新規登録は RegisterController にて処理している
    // ログインは LoginController にて処理している

    public function delete($id) 
    {
        $user = User::findOrFail($id); // 指定されたIDのユーザーを取得

        if ($user) { // 質問が存在するか確認
            $user->delete(); // 問題を削除
            return redirect()->back()->with('success', 'User deleted successfully.'); // 削除後に前のページにリダイレクト
        } else {
            return redirect()->back()->with('error', 'User not found.'); // 問題が存在しない場合、エラーメッセージと共にリダイレクト
        }
    }
}
