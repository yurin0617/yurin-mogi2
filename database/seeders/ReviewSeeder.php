<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        // 評価に応じたサンプルのコメントを用意
        $comments = [
            5 => ['とても良かったです！', '最高の一冊でした。', '何度も読み返しています。'],
            4 => ['勉強になりました。', '読みやすくて面白かったです。', '期待通りの内容でした。'],
            3 => ['普通でした。', '可もなく不可もなくという感じ。', '参考にはなりました。'],
        ];

        $totalReviews = 0;
        $targetTotal = 32;

        foreach ($books as $index => $book) {
            // 各書籍に2〜4件のレビューを配分（合計が32件になるように調整）
            $count = ($index === count($books) - 1)
                ? ($targetTotal - $totalReviews)
                : rand(2, 4);

            // 念のための範囲制御
            $count = max(2, min(4, $count));
            if ($totalReviews + $count > $targetTotal) {
                $count = $targetTotal - $totalReviews;
            }

            for ($i = 0; $i < $count; $i++) {
                $user = $users->random(); // 5人からランダムに選択
                $rating = rand(3, 5);     // 3〜5の評価
                $commentList = $comments[$rating];
                $comment = $commentList[array_rand($commentList)];

                Review::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'rating' => $rating,
                    'comment' => $comment,
                ]);

                $totalReviews++;
            }
        }

        // 万が一32件に満たなかった場合の調整用
        while ($totalReviews < $targetTotal) {
            $book = $books->random();
            $user = $users->random();
            $rating = rand(3, 5);
            $commentList = $comments[$rating];
            $comment = $commentList[array_rand($commentList)];

            Review::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'rating' => $rating,
                'comment' => $comment,
            ]);
            $totalReviews++;
        }
    }
}
