<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * お気に入り機能：ログインユーザーが書籍をお気に入り登録（トグル）できるか
     */
    public function test_authenticated_user_can_toggle_favorite()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $book = Book::create([
            'title' => 'テスト書籍タイトル',
            'author' => 'テスト著者名',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);

        // お気に入り登録のエンドポイントへPOSTリクエストを送信
        $response = $this->actingAs($user)->post(route('favorites.toggle', $book));

        $response->assertRedirect();

        // データベースのテーブル名を book_user_favorites に修正
        $this->assertDatabaseHas('book_user_favorites', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * お気に入り機能：ログインユーザーがお気に入り一覧画面にアクセスできるか
     */
    public function test_authenticated_user_can_access_favorites_index()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get(route('favorites.index'));

        $response->assertStatus(200);
    }
}
