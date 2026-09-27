<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        foreach ($users as $user) {
            // 各ユーザーに3〜5冊のお気に入りをランダムに設定
            $count = rand(3, 5);
            $randomBooks = $books->random($count);

            // syncWithoutDetaching を使って重複を防ぎつつ中間テーブルに登録
            $user->favoriteBooks()->syncWithoutDetaching($randomBooks->pluck('id'));
        }
    }
}
