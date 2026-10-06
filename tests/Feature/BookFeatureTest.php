<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証：ログイン画面が正常に表示されるか
     */
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * 画面アクセス：ログインユーザーが書籍一覧画面にアクセスできるか
     */
    public function test_authenticated_user_can_access_books_index()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/books');
        $response->assertStatus(200);
    }

    /**
     * 書籍CRUD：ログインユーザーが書籍を登録できるか
     */
    public function test_authenticated_user_can_create_book()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $genre = Genre::create([
            'name' => '技術書',
        ]);

        $response = $this->actingAs($user)->post('/books', [
            'title' => 'テスト書籍タイトル',
            'author' => 'テスト著者名',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'genres' => [$genre->id],
        ]);

        $response->assertValid();
        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'title' => 'テスト書籍タイトル',
            'author' => 'テスト著者名',
            'user_id' => $user->id,
        ]);
    }

    /**
     * 書籍CRUD：自分の登録した書籍を更新できるか
     */
    public function test_authenticated_user_can_update_their_own_book()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $genre = Genre::create([
            'name' => '技術書',
        ]);

        // あらかじめ本を登録しておく
        $book = Book::create([
            'title' => '古いタイトル',
            'author' => 'テスト著者',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);
        $book->genres()->sync([$genre->id]);

        // 更新リクエスト（PUT）を送信
        $response = $this->actingAs($user)->put(route('books.update', $book), [
            'title' => '新しいタイトルに更新',
            'author' => 'テスト著者',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'genres' => [$genre->id],
        ]);

        $response->assertValid();
        $response->assertRedirect(route('books.show', $book));

        // データベースが更新されているか
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => '新しいタイトルに更新',
        ]);
    }

    /**
     * 認可：他のユーザーの書籍は編集・更新できず 403 になるか
     */
    public function test_authenticated_user_cannot_update_others_book()
    {
        $owner = User::create([
            'name' => '所有者ユーザー',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
        ]);

        $otherUser = User::create([
            'name' => '別ユーザー',
            'email' => 'other@example.com',
            'password' => bcrypt('password'),
        ]);

        $genre = Genre::create([
            'name' => '技術書',
        ]);

        // 所有者が本を作成
        $book = Book::create([
            'title' => '所有者の本',
            'author' => 'テスト著者',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'user_id' => $owner->id,
        ]);

        // 別ユーザーとしてログインして、所有者の本を更新しようとする
        $response = $this->actingAs($otherUser)->put(route('books.update', $book), [
            'title' => '勝手に書き換え',
            'author' => 'テスト著者',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'genres' => [$genre->id],
        ]);

        // 403 Forbidden が返ってくることを確認
        $response->assertStatus(403);
    }

    /**
     * 書籍CRUD：自分の登録した書籍を削除できるか
     */
    public function test_authenticated_user_can_delete_their_own_book()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $book = Book::create([
            'title' => '削除する本',
            'author' => 'テスト著者',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);

        // 削除リクエスト（DELETE）を送信
        $response = $this->actingAs($user)->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));

        // データベースから消えている（SoftDeleteでなければMissing）ことを確認
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}
