<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ユーザーが登録した書籍一覧（1対多）
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * ユーザーが投稿したレビュー一覧（1対多）
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * ユーザーがお気に入り登録している書籍一覧（多対多）
     * ※カスタム中間テーブル名 'book_user_favorites' を指定
     */
    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'book_user_favorites', 'user_id', 'book_id');
    }

    /**
     * ユーザーが「いいね」しているレビュー一覧（多対多）
     */
    public function likedReviews()
    {
        return $this->belongsToMany(Review::class, 'review_likes');
    }

    /**
     * ユーザーの読書計画一覧（1対多・応用）
     */
    public function readingPlans()
    {
        return $this->hasMany(ReadingPlan::class);
    }
}
