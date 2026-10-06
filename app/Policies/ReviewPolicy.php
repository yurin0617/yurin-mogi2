<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * ユーザーがそのレビューを更新できるかどうか
     */
    public function update(User $user, Review $review): bool
    {
        // ログイン中のユーザーのIDと、レビューの投稿者IDが一致していればtrue（許可）
        return $user->id === $review->user_id;
    }

    /**
     * ユーザーがそのレビューを削除できるかどうか
     */
    public function delete(User $user, Review $review): bool
    {
        // ログイン中のユーザーのIDと、レビューの投稿者IDが一致していればtrue（許可）
        return $user->id === $review->user_id;
    }
}
