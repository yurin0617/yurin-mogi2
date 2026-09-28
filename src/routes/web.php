<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;

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

// ランキング機能を作るまでの仮のダミー定義（エラー回避用）
Route::get('/ranking', function () {
    return 'ランキングページ（準備中）';
})->name('ranking.index');
// お気に入り機能を作るまでの仮のダミー定義（エラー回避用）
Route::get('/favorites', function () {
    return 'お気に入りページ（準備中）';
})->name('favorites.index');
// --- 以下、今後のマイルストーンで実装するまでの仮のダミー定義（エラー回避用） ---
Route::get('/ranking', function () {
    return 'ランキングページ（準備中）';
})->name('ranking.index');

Route::get('/favorites', function () {
    return 'お気に入りページ（準備中）';
})->name('favorites.index');

Route::get('/genres', function () {
    return 'ジャンルページ（準備中）';
})->name('genres.index');
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