<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AuthController;

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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');

Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');

Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show');

Route::get('/questions/{id}/check', [QuestionController::class, 'checkAnswer'])->name('questions.check');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
