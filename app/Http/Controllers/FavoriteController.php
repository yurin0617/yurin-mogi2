<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * お気に入り一覧画面
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // ログインしていなければログインページへリダイレクト
        if (! $user) {
            return redirect()->route('login');
        }

        // ログインユーザーのお気に入り書籍をページネーション付きで取得
        $books = $user->favoriteBooks()->paginate(10);

        return view('favorites.index', compact('books'));
    }

    /**
     * お気に入りのトグル（登録 / 解除）処理
     */
    public function store(Book $book)
    {
        /** @var User $user */
        // ログイン中のユーザーを取得（※一時的にAuth::id()を使用。認証機能実装後にそのまま連動します）
        $user = Auth::user();

        // ★未ログインの場合はログインページへリダイレクト
        if (! $user) {
            return redirect()->route('login');
        }
        // すでにお気に入り登録しているかチェック
        if ($user->favoriteBooks()->where('book_id', $book->id)->exists()) {
            // 登録済みの場合は解除（デタッチ）
            $user->favoriteBooks()->detach($book->id);
            $message = 'お気に入りから解除しました。';
        } else {
            // 未登録の場合は登録（アタッチ）
            $user->favoriteBooks()->attach($book->id);
            $message = 'お気に入りに追加しました。';
        }

        return back()->with('success', $message);
    }
}
