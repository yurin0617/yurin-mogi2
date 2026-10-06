<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookRelationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 書籍がユーザーに属しているか（belongsTo）のテスト
     */
    public function test_book_belongs_to_user()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $book = new Book;
        $book->title = 'Laravel入門';
        $book->author = 'テスト著者A';
        $book->user_id = $user->id;
        $book->save();

        // 書籍に紐づくユーザーが正しく取得できるか
        $this->assertInstanceOf(User::class, $book->user);
        $this->assertEquals($user->id, $book->user->id);
    }

    /**
     * 書籍が複数のレビューを持っているか（hasMany）のテスト
     */
    public function test_book_has_many_reviews()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $book = new Book;
        $book->title = 'Laravel実践';
        $book->author = 'テスト著者B';
        $book->user_id = $user->id;
        $book->save();

        $review = Review::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'comment' => 'とても分かりやすい本でした！',
            'rating' => 5,
        ]);

        // 書籍に紐づくレビューコレクションに含まれているか
        $this->assertTrue($book->reviews->contains($review));
        $this->assertInstanceOf(Review::class, $book->reviews->first());
    }
}
