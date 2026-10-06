<?php

namespace Tests\Feature\Api\V1;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * API V1: 書籍一覧が正常に取得できるか
     */
    public function test_can_get_books_list_via_api()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Book::create([
            'title' => 'APIテスト用書籍',
            'author' => 'APIテスト著者',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/v1/books');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'APIテスト用書籍',
            ]);
    }

    /**
     * API V1: 指定した書籍の詳細が正常に取得できるか
     */
    public function test_can_get_single_book_via_api()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $book = Book::create([
            'title' => 'API詳細テスト用書籍',
            'author' => 'APIテスト著者',
            'isbn' => '9784987654321',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'API詳細テスト用書籍',
            ]);
    }

    /**
     * API V1: 書籍が新しく登録できるか
     */
    public function test_can_create_book_via_api()
    {
        // ▼ 500エラーの正確なスタックトレースをコンソールに出力させる
        $this->withoutExceptionHandling();

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $genre = Genre::create([
            'name' => 'テストジャンル',
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/books', [
            'title' => 'API新規登録書籍',
            'author' => 'API新規著者',
            'isbn' => '9784111111111',
            'published_date' => '2026-01-01',
            'genres' => [$genre->id],
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('books', [
            'title' => 'API新規登録書籍',
        ]);
    }

    /**
     * API V1: 書籍情報が更新できるか
     */
    public function test_can_update_book_via_api()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $genre = Genre::create([
            'name' => 'テストジャンル',
        ]);

        $book = Book::create([
            'title' => 'API更新前書籍',
            'author' => 'API著者',
            'isbn' => '9784222222222',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->putJson("/api/v1/books/{$book->id}", [
            'title' => 'API更新後書籍',
            'author' => 'API著者',
            'isbn' => '9784222222222',
            'published_date' => '2026-01-01',
            'genres' => [$genre->id],
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'API更新後書籍',
        ]);
    }

    /**
     * API V1: 書籍が削除できるか
     */
    public function test_can_delete_book_via_api()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $book = Book::create([
            'title' => 'API削除用書籍',
            'author' => 'API著者',
            'isbn' => '9784333333333',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/v1/books/{$book->id}");

        $response->assertSuccessful();

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}
