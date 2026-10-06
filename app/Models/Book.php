<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'isbn',
        'published_date',
        'description',
        'image_url',
    ];

    // 登録したユーザー（1対多・逆側）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 書籍のジャンル（多対多）
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

    // 書籍のレビュー（1対多）
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // この本をお気に入り登録しているユーザーたち（多対多）
    public function favoritedUsers()
    {
        return $this->belongsToMany(User::class, 'book_user_favorites', 'book_id', 'user_id');
    }

    // 読書計画（1対多・応用）
    public function readingPlans()
    {
        return $this->hasMany(ReadingPlan::class);
    }
}
