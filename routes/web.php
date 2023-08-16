<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
// use App\Http\Controllers\AuthController;
use App\Http\Controllers\MyPageController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');

Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');

Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show');

Route::get('/questions/{id}/check', [QuestionController::class, 'checkAnswer'])->name('questions.check');

Route::get('/questions/{id}/detail', [QuestionController::class, 'detail'])->name('questions.detail');

Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');

Route::get('/password/reset', [AuthController::class, 'showPasswordResetForm'])->name('password.request');

Route::get('/questions/{id}/question', [QuestionController::class, 'showQuestionForm'])->name('questions.question_form');

Route::get('/questions/{id}/hint', [QuestionController::class, 'showHintForm'])->name('questions.hint_form');

Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index'); // ここでRankingControllerを使用

Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// ログイン処理
Route::post('login', [LoginController::class, 'login']);

// この部分を変更
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::post('register', [RegisterController::class, 'register']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::post('/generate-question', [QuestionController::class, 'generateQuestion'])->name('generate-question');

Route::get('/questions/generated', 'QuestionController@showGenerated')->name('questions.generated');

Route::get('/generate-question-form', function () {
    return view('questions.generate_form');
});
