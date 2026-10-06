<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * ユーザーがその書籍を更新できるかどうか
     */
    public function update(User $user, Book $book): bool
    {
        // ログイン中のユーザーのIDと、書籍の登録者IDが一致していればtrue（許可）
        return $user->id === $book->user_id;
    }

    /**
     * ユーザーがその書籍を削除できるかどうか
     */
    public function delete(User $user, Book $book): bool
    {
        // ログイン中のユーザーのIDと、書籍の登録者IDが一致していればtrue（許可）
        return $user->id === $book->user_id;
    }
}
