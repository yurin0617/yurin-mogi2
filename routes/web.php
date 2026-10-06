<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

// ▼ ★ここに「トップページ（/）にアクセスしたときのルート」を追加します！
Route::get('/', [BookController::class, 'index']);

// 書籍管理機能のCRUDルーティング
Route::resource('books', BookController::class);

// お気に入り一覧画面（要ログイン）
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');

// お気に入りトグル処理（ルート名をビューに合わせて 'favorites.toggle' にする）
Route::post('/books/{book}/favorites', [FavoriteController::class, 'store'])->name('favorites.toggle');

// ==========================================
// レビュー関連のルーティング
// ==========================================
Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// レビューへのいいねトグル処理
Route::post('/reviews/{review}/like', [ReviewLikeController::class, 'store'])->name('reviews.like');

// ランキング一覧画面
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');

// ジャンル管理のCRUDルーティング
Route::resource('genres', GenreController::class);

// --- 会員登録関連 ---
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// --- ログイン関連 ---
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// --- ログアウト ---
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
