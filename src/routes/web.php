<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\GenreController;

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

// 書籍管理機能のCRUDルーティング
Route::resource('books', BookController::class);

// レビュー機能のルーティング（一覧や詳細画面は不要なため、store, edit, update, destroyのみを対象にする）
Route::resource('books.reviews', ReviewController::class)->only([
    'store',
    'edit',
    'update',
    'destroy'
]);

// お気に入りトグル処理
Route::post('/books/{book}/favorites', [FavoriteController::class, 'store'])->name('favorites.store');

// お気に入り一覧画面
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

// レビューへのいいねトグル処理
Route::post('/reviews/{review}/like', [ReviewLikeController::class, 'store'])->name('reviews.like');

// ランキング一覧画面
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');

// ジャンル管理のCRUDルーティング
Route::resource('genres', GenreController::class);

// --- 以下、今後のマイルストーンで実装するまでの仮のダミー定義（エラー回避用） ---
// ログインページの仮定義（エラー回避用）
Route::get('/login', function () {
    return 'ログインページ（準備中）';
})->name('login');
// 認証・その他の未実装機能の仮定義（エラー回避用）
Route::get('/register', function () {
    return 'ユーザー登録ページ（準備中）';
})->name('register');

Route::get('/dashboard', function () {
    return 'ダッシュボード（準備中）';
})->name('dashboard');
