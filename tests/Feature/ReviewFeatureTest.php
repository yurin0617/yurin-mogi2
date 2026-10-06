<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * レビュー機能：ログインユーザーが書籍に対してレビューを投稿できるか
     */
    public function test_authenticated_user_can_create_review()
    {
        // 1. ユーザーの作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. 評価対象となる書籍の作成（レビュー対象の本）
        $book = Book::create([
            'title' => 'テスト書籍タイトル',
            'author' => 'テスト著者名',
            'isbn' => '9784123456789',
            'published_date' => '2026-01-01',
            'user_id' => $user->id,
        ]);

        // 3. レビュー投稿のエンドポイントへPOSTリクエストを送信
        $response = $this->actingAs($user)->post(route('reviews.store', $book), [
            'rating' => 5,
            'comment' => 'とても勉強になる素晴らしい本でした！',
        ]);

        // 4. バリデーションエラーがなく、適切な場所にリダイレクトされるか
        $response->assertValid();
        $response->assertRedirect();

        // 5. データベースにレビューが正しく保存されているか
        $this->assertDatabaseHas('reviews', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'とても勉強になる素晴らしい本でした！',
        ]);
    }

    /**
     * レビュー機能：自分のレビューを更新できるか
     */
    public function test_authenticated_user_can_update_their_own_review()
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

        // テスト用のレビューをあらかじめ作成
        $review = Review::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'rating' => 3,
            'comment' => '古いコメント',
        ]);

        // 更新リクエスト（PUT）を送信
        $response = $this->actingAs($user)->put(route('reviews.update', $review), [
            'rating' => 5,
            'comment' => '更新された素晴らしいコメントです！',
        ]);

        $response->assertValid();
        $response->assertRedirect();

        // データベースが更新されているか確認
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 5,
            'comment' => '更新された素晴らしいコメントです！',
        ]);
    }

    /**
     * レビュー機能：自分のレビューを削除できるか
     */
    public function test_authenticated_user_can_delete_their_own_review()
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

        $review = Review::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => '削除するレビューです',
        ]);

        // 削除リクエスト（DELETE）を送信
        $response = $this->actingAs($user)->delete(route('reviews.destroy', $review));

        $response->assertRedirect();

        // データベースから消えていることを確認
        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    /**
     * 認可：他のユーザーのレビューは編集・削除できず 403 になるか
     */
    public function test_authenticated_user_cannot_update_or_delete_others_review()
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
            'comment' => '所有者のレビュー',
        ]);

        // 別ユーザーとして編集しようとする → 403
        $responseUpdate = $this->actingAs($otherUser)->put(route('reviews.update', $review), [
            'rating' => 1,
            'comment' => '勝手に書き換え',
        ]);
        $responseUpdate->assertStatus(403);

        // 別ユーザーとして削除しようとする → 403
        $responseDestroy = $this->actingAs($otherUser)->delete(route('reviews.destroy', $review));
        $responseDestroy->assertStatus(403);
    }
}
