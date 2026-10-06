<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ジャンル機能：ログインユーザーがジャンル一覧画面にアクセスできるか
     */
    public function test_authenticated_user_can_access_genres_index()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get(route('genres.index'));

        $response->assertStatus(200);
    }

    /**
     * ジャンル機能：ログインユーザーが新しいジャンルを登録できるか
     */
    public function test_authenticated_user_can_create_genre()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->post(route('genres.store'), [
            'name' => '小説・エッセイ',
        ]);

        $response->assertValid();
        $response->assertRedirect();

        $this->assertDatabaseHas('genres', [
            'name' => '小説・エッセイ',
        ]);
    }

    /**
     * ジャンル機能：ログインユーザーがジャンルを更新できるか
     */
    public function test_authenticated_user_can_update_genre()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $genre = Genre::create([
            'name' => '旧ジャンル名',
        ]);

        $response = $this->actingAs($user)->put(route('genres.update', $genre), [
            'name' => '新ジャンル名',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => '新ジャンル名',
        ]);
    }

    /**
     * ジャンル機能：ログインユーザーがジャンルを削除できるか
     */
    public function test_authenticated_user_can_delete_genre()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $genre = Genre::create([
            'name' => '削除用ジャンル',
        ]);

        $response = $this->actingAs($user)->delete(route('genres.destroy', $genre));

        $response->assertRedirect();

        $this->assertDatabaseMissing('genres', [
            'id' => $genre->id,
        ]);
    }
}
