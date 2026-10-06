<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * ジャンル一覧画面
     */
    public function index()
    {
        $genres = Genre::withCount('books')->get();

        return view('genres.index', compact('genres'));
    }

    /**
     * ジャンル登録画面
     */
    public function create()
    {
        return view('genres.create');
    }

    /**
     * ジャンル登録処理
     */
    public function store(GenreRequest $request)
    {
        Genre::create($request->validated());

        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを追加しました。');
    }

    /**
     * ジャンル詳細画面（必要に応じて）
     */
    public function show(Genre $genre)
    {
        // ジャンルに紐づく書籍をページネーションで取得
        $books = $genre->books()->paginate(10);

        // compact に $books を追加してビューに渡す
        return view('genres.show', compact('genre', 'books'));
    }

    /**
     * ジャンル編集画面
     */
    public function edit(Genre $genre)
    {
        return view('genres.edit', compact('genre'));
    }

    /**
     * ジャンル更新処理
     */
    public function update(GenreRequest $request, Genre $genre)
    {
        $genre->update($request->validated());

        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを更新しました。');
    }

    /**
     * ジャンル削除処理
     */
    public function destroy(Genre $genre)
    {
        // ジャンルに紐付く書籍が存在するかチェック
        if ($genre->books()->exists()) {
            return redirect()->route('genres.index')
                ->with('error', 'このジャンルには書籍が紐付いているため削除できません。');
        }

        $genre->delete();

        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを削除しました。');
    }
}
