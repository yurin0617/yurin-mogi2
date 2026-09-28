<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限を持っているか
     */
    public function authorize(): bool
    {
        return true; // 誰でもリクエスト可能にするため true に変更
    }

    /**
     * バリデーションルールを定義
     */
    public function rules(): array
    {
        // 編集時のユニークチェックで、自分自身のISBN重複エラーを防ぐためのID取得
        $bookId = $this->route('book') ? $this->route('book')->id : null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            // ISBNは13桁、かつ books テーブル内で重複しない（自分のIDは除外）
            'isbn' => ['required', 'string', 'size:13', 'unique:books,isbn,' . $bookId],
            'published_date' => ['required', 'date'],
            // ジャンルは配列で受け取り、1つ以上選択されていること（min:1）
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['exists:genres,id'], // 存在しているジャンルIDかチェック
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
        ];
    }
}
