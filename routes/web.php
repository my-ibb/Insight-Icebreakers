<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\Auth\LoginController; 
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//　トップページ
Route::get('/', [HomeController::class, 'index'])->name('home');

//　ーーーここからウミガメの問題関連ーーー

// ウミガメの問題一覧ページは下記
Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');

// 新規問題作成ページは下記
Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');

// 問題文をGPTのAPIを叩いて作成しているのは下記
// create.bladeのフォームからPOST送信される
Route::post('/generate-question', [QuestionController::class, 'generateQuestion'])->name('generate-question');

//　作られた問題をデータベースに保存するのは下記
// create.bladeのボタンからPOST送信される
Route::post('/save-question', [QuestionController::class, 'saveQuestion'])->name('save-question');


// 生成した問題のプレビュー画面（DB保存前）
Route::get('/questions/generated',  [QuestionController::class, 'showGenerated'])->name('questions.generated');

//質問のチャット、ヒントのページ
Route::post('/generate-hint', [QuestionController::class, 'generateHint'])->name('generate-hint');
Route::post('/generate-chat-response', [QuestionController::class, 'generateChatResponse'])->name('generate-chat-response');

// 回答フォーム
Route::post('/checkAnswer/{id}', [QuestionController::class, 'checkAnswer'])->name('checkAnswer');

//新しくつくった正解確認画面
Route::get('/check/{id}', [QuestionController::class, 'checkAnswer'])->name('check-answer');

Route::get('/questions/{id}/check', [QuestionController::class, 'checkAnswer'])->name('questions.check');//問題一覧の問題のこたえのやつ？？

// 下記あとで消すかも（detail or question）
Route::get('/questions/{id}/detail', [QuestionController::class, 'detail'])->name('questions.detail');
Route::get('/questions/{id}/question', [QuestionController::class, 'showQuestionForm'])->name('questions.question_form');


Route::get('/questions/{id}/hint', [QuestionController::class, 'showHintForm'])->name('questions.hint_form');
Route::get('/getHint/{questionId}', [QuestionController::class, 'getHint'])->name('questions.hint');

Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');

//
Route::post('/questions/storeAnswer', [QuestionController::class, 'storeAnswer'])->name('questions.storeAnswer');

Route::get('/questions/{id}/input', [QuestionController::class, 'inputQuestion'])->name('questions.input');

Route::post('/questions/{id}/storeQuestion', [QuestionController::class, 'storeQuestion'])->name('questions.storeQuestion');

//　ランキング関連
Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index'); // ここでRankingControllerを使用

//　ユーザー関連
Route::get('/mypage', [UserController::class, 'index'])->name('mypage');
//　新規登録
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// ログイン処理
Route::post('login', [LoginController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');



