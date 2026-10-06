<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewLikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * レビューいいね機能：ログインユーザーがレビューに対していいねできるか
     */
    public function test_authenticated_user_can_like_review()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $owner = User::create([
            'name' => '所有者ユーザー',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
        ]);

        $book = Book::create([
            'title' => 'テスト書籍タイトル',
            'author' => 'テスト著者名',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'user_id' => $owner->id,
        ]);

        $review = Review::create([
            'book_id' => $book->id,
            'user_id' => $owner->id,
            'rating' => 5,
            'comment' => 'とても良い本でした！',
        ]);

        // 正しいルート名 'reviews.like' を指定
        $response = $this->actingAs($user)->post(route('reviews.like', $review));

        $response->assertRedirect();
    }
}
