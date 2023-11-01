<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\Auth\LoginController; 
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SelfIntroductionLieGameController;
use App\Http\Controllers\AdminController;

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

Route::middleware(['auth'])->group(function () {
    // 新規問題作成ページは下記
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    //質問のチャット、ヒントのページ
    Route::post('/generate-hint', [QuestionController::class, 'generateHint'])->name('generate-hint'); 
    Route::post('/generate-chat-response', [QuestionController::class, 'generateChatResponse'])->name('generate-chat-response');
    
    // 回答フォームから正解かどうか判定する
    Route::post('/checkAnswer/{id}', [QuestionController::class, 'checkAnswer'])->name('checkAnswer');
    
    // 正解だった場合にresult画面に遷移する
    Route::get('/questions/{questionId}/result/{questionCount}/{hintCount}', [ScoreController::class, 'result'])->name('result');
    //　問題解くページ？
    Route::get('/questions/{id}/detail', [QuestionController::class, 'detail'])->name('questions.detail');
    Route::get('/questions/{id}/question', [QuestionController::class, 'showQuestionForm'])->name('questions.question_form');

    Route::post('/questions/storeAnswer', [QuestionController::class, 'storeAnswer'])->name('questions.storeAnswer');

    Route::get('/questions/{id}/input', [QuestionController::class, 'inputQuestion'])->name('questions.input');

    Route::post('/questions/{id}/storeQuestion', [QuestionController::class, 'storeQuestion'])->name('questions.storeQuestion');

    //　ランキング関連　//RankingControllerのshowメソッドにルーティング
    Route::get('questions/{questionId}/ranking', [RankingController::class, 'show']);
    Route::post('questions/{questionId}/store-score', [ScoreController::class, 'store'])->name('storeScore');
    
});


//　ーーーここからウミガメの問題関連ーーー

// ウミガメの問題一覧ページは下記
Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');

// 問題文をGPTのAPIを叩いて作成しているのは下記
// create.bladeのフォームからPOST送信される
Route::post('/generate-question', [QuestionController::class, 'generateQuestion'])->name('generate-question');

//　作られた問題をデータベースに保存するのは下記
// create.bladeのボタンからPOST送信される
Route::post('/save-question', [QuestionController::class, 'saveQuestion'])->name('save-question');

Route::get('/questions/{id}/hint', [QuestionController::class, 'showHintForm'])->name('questions.hint_form'); //つかってないかも
Route::get('/getHint/{questionId}', [QuestionController::class, 'getHint'])->name('questions.hint'); //つかってないかも



//　ユーザー関連
Route::get('/mypage', [UserController::class, 'index'])->name('mypage');

Route::post('register', [RegisterController::class, 'register']);

// ログイン処理
Route::middleware([
    'cache.headers:no_store;max_age=0',
])->group(function (): void {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    //　新規登録
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // 管理者 - ログイン処理
    Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
});

Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::post('login', [LoginController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// ホームページから自己紹介嘘当てゲームへ
Route::get('self-introduction-lie-game', [SelfIntroductionLieGameController::class, 'index'])->name('selfIntroductionLieGame.index');

// routes/web.php または routes/api.php
Route::group(['middleware' => ['web']], function () {

Route::post('/start', [SelfIntroductionLieGameController::class, 'start'])->name('selfIntroductionLieGame.start');

Route::get('/setup', [SelfIntroductionLieGameController::class, 'setup'])->name('selfIntroductionLieGame.setup');
});

//自己紹介設問画面から自己紹介表示画面へ
Route::get('/self-introduction-display', [SelfIntroductionLieGameController::class, 'display'])->name('selfIntroductionLieGame.display');
// 自己紹介要約する
Route::post('/storeTruthAndLie', [SelfIntroductionLieGameController::class, 'storeTruthAndLie'])->name('selfIntroductionLieGame.storeTruthAndLie');

// セッションをリセットする
Route::post('/selfIntroductionLieGame/reset', [SelfIntroductionLieGameController::class, 'reset'])
    ->name('selfIntroductionLieGame.reset');

// Questionsの部分
Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::patch('/questions/{id}', [QuestionController::class, 'updateQuestion'])->name('questions.update');
Route::delete('/questions/{id}', [QuestionController::class, 'delete'])->name('questions.delete');

Route::get('/self-introductions/create', [SelfIntroductionLieGameController::class, 'create'])->name('self-introductions.create');

Route::get('/self-introductions/{id}/edit', [SelfIntroductionLieGameController::class, 'edit'])->name('self-introductions.edit');
Route::delete('/self-introductions/{id}', [SelfIntroductionLieGameController::class, 'destroy'])->name('self-introductions.delete');


// ーーー管理者関連はここからーーー
Route::middleware(['admin'])->group(function () {
    // 管理者 - User関連
    Route::get('/admin/dashboard/users', [AdminController::class, 'dashboardUsers'])->name('admin.dashboard.users');
    Route::delete('/user/{id}/delete', [UserController::class, 'delete'])->name('user.delete');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    
    // 管理者 - Question関連
    Route::get('/admin/dashboard/questions', [AdminController::class, 'dashboardQuestions'])->name('admin.dashboard.questions');
    Route::delete('/question/{id}/delete', [QuestionController::class, 'delete'])->name('question.delete');
    Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update');
    
    // 管理者 - SelfIntroductionLieGame関連
    Route::get('/admin/dashboard/self-introduction-questions', [AdminController::class, 'dashboardSelfIntroductionQuestions'])->name('admin.dashboard.self_introduction_questions');
    Route::delete('/self-introduction/{id}/delete', [SelfIntroductionLieGameController::class, 'delete'])->name('self-introduction.delete');
    Route::get('/self-introduction/{id}/edit', [SelfIntroductionLieGameController::class, 'edit'])->name('self-introduction.edit');
    Route::put('/self-introduction/{id}/update', [SelfIntroductionLieGameController::class, 'update'])->name('self-introduction.update');
    
    Route::get('/admin/questions/create', [SelfIntroductionLieGameController::class, 'createQuestionForm'])->name('admin.questions.create');
    Route::post('/admin/questions', [SelfIntroductionLieGameController::class, 'storeQuestion'])->name('admin.questions.store');
        
});




