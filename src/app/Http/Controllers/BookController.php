<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * 書籍一覧画面
     */
    public function index()
    {
        // 投稿者情報とジャンルを一緒に取得（N+1問題対策）して新しい順にページネーション
        $books = Book::with(['user', 'genres'])->latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * 書籍登録画面
     */
    public function create()
    {
        $genres = Genre::all();
        return view('books.create', compact('genres'));
    }

    /**
     * 書籍の保存処理
     */
    public function store(BookRequest $request)
    {
        // ログイン中のユーザーの書籍として新規作成
        $book = Auth::user()->books()->create($request->validated());

        // 選択されたジャンルを中間テーブルに紐づけ
        $book->genres()->sync($request->genres);

        return redirect()->route('books.index')->with('success', '書籍を登録しました。');
    }

    /**
     * 書籍詳細画面
     */
    public function show(Book $book)
    {
        // 関連するデータ（投稿者、ジャンル、レビューとその投稿者など）をロード
        $book->load(['user', 'genres', 'reviews.user']);
        return view('books.show', compact('book'));
    }

    /**
     * 書籍編集画面
     */
    public function edit(Book $book)
    {
        // Policyを使って「作成者本人か」をチェック
        $this->authorize('update', $book);

        $genres = Genre::all();
        return view('books.edit', compact('book', 'genres'));
    }

    /**
     * 書籍更新処理
     */
    public function update(BookRequest $request, Book $book)
    {
        // Policyを使って「作成者本人か」をチェック
        $this->authorize('update', $book);

        $book->update($request->validated());
        $book->genres()->sync($request->genres);

        return redirect()->route('books.show', $book)->with('success', '書籍を更新しました。');
    }

    /**
     * 書籍削除処理
     */
    public function destroy(Book $book)
    {
        // Policyを使って「作成者本人か」をチェック
        $this->authorize('delete', $book);

        $book->delete();

        return redirect()->route('books.index')->with('success', '書籍を削除しました。');
    }
}
