<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 登録画面が正常に表示されるか
     */
    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * 新規ユーザー登録ができるか
     */
    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => '新規テストユーザー',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 認証されてトップページにリダイレクトされることを確認
        $this->assertAuthenticated();
        $response->assertRedirect('/');

        // データベースにユーザーが保存されているか確認
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }
}
