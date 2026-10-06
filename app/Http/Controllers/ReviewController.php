<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * レビューの保存処理
     */
    public function store(ReviewRequest $request, Book $book)
    {
        // ログイン中のユーザーIDと書籍IDを紐づけてレビューを作成
        $book->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('books.show', $book)->with('success', 'レビューを投稿しました。');
    }

    /**
     * レビュー編集画面
     */
    public function edit(Review $review)
    {
        // Policyを使って本人が書いたレビューかチェック
        $this->authorize('update', $review);

        $book = $review->book;

        return view('reviews.edit', compact('book', 'review'));
    }

    /**
     * レビュー更新処理
     */
    public function update(ReviewRequest $request, Review $review)
    {
        // Policyを使って本人が書いたレビューかチェック
        $this->authorize('update', $review);

        $review->update($request->validated());

        return redirect()->route('books.show', $review->book_id)->with('success', 'レビューを更新しました。');
    }

    /**
     * レビュー削除処理
     */
    public function destroy(Review $review)
    {
        // Policyを使って本人が書いたレビューかチェック
        $this->authorize('delete', $review);

        $bookId = $review->book_id;
        $review->delete();

        return redirect()->route('books.show', $bookId)->with('success', 'レビューを削除しました。');
    }
}
