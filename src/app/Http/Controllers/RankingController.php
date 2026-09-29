<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * ランキング一覧画面
     */
    public function index()
    {
        // 変数名を $books から $rankedBooks に変更
        $rankedBooks = Book::withAvg('reviews', 'rating')
            ->has('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        return view('ranking.index', compact('rankedBooks'));
    }
}
