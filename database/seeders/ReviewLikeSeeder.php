<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewLikeSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $reviews = Review::all();

        foreach ($reviews as $review) {
            // 各レビューに0〜3人のユーザーがいいねをする
            $likeCount = rand(0, 3);
            if ($likeCount === 0) {
                continue; // 0人の場合は何もしない
            }

            // 「自分のレビューを書いた本人以外のユーザー」を抽出
            $eligibleUsers = $users->where('id', '!=', $review->user_id);

            // 選択可能な人数が希望数より少ない場合は調整
            $actualCount = min($likeCount, $eligibleUsers->count());

            if ($actualCount > 0) {
                $randomUsers = $eligibleUsers->random($actualCount);

                // syncWithoutDetaching を使っていいねを登録
                $review->likedByUsers()->syncWithoutDetaching($randomUsers->pluck('id'));
            }
        }
    }
}
