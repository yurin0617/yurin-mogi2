<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ランキング機能：ログインユーザーがランキング画面にアクセスできるか
     */
    public function test_authenticated_user_can_access_ranking_index()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get(route('ranking.index'));

        $response->assertStatus(200);
    }
}
