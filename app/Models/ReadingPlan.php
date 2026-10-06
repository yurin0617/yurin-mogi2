<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'target_date',
        'status',
        'completed_at',
    ];

    // 計画者（1対多・逆側）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 対象の書籍（1対多・逆側）
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
