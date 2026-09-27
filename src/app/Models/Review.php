<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'comment',
    ];

    // 投稿者（1対多・逆側）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 対象の書籍（1対多・逆側）
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * レビューを「いいね」しているユーザー一覧（多対多）
     */
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'review_likes');
    }
}
