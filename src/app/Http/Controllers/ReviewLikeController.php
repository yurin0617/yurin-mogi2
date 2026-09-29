<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewLikeController extends Controller
{
    /**
     * レビューのいいねトグル（登録 / 解除）処理
     */
    public function store(Review $review)
    {
        /** @var User $user */ // ← ② このコメントで「これはUserモデルだよ」とエディタに教える
        $user = Auth::user();

        // ★追加：自分のレビューには「いいね」できないように制限
        if ($review->user_id === $user->id) {
            return back()->with('error', '自分のレビューにはいいねできません。');
        }

        // すでに「いいね」しているかチェック
        if ($user->likedReviews()->where('review_id', $review->id)->exists()) {
            // いいね解除（デタッチ）
            $user->likedReviews()->detach($review->id);
            $message = 'いいねを解除しました。';
        } else {
            // いいね追加（アタッチ）
            $user->likedReviews()->attach($review->id);
            $message = 'いいねしました！';
        }

        return back()->with('success', $message);
    }
}
