<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限を持っているか
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールを定義
     */
    public function rules(): array
    {
        // 編集時のユニークチェックで、自分自身のISBN重複エラーを防ぐためのID取得
        $book = $this->route('book');
        $bookId = $book instanceof Book ? $book->id : $book;

        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            // ISBNは必須、13桁の数値、かつ books テーブル内で重複しない（自分のIDは除外）
            'isbn' => [
                'required',
                'digits:13',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'published_date' => ['required', 'date'],
            // ジャンルは配列で受け取り、1つ以上選択されていること（min:1）
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['exists:genres,id'], // 存在しているジャンルIDかチェック
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',

            'author.required' => '著者名は必須です。',
            'author.max' => '著者名は255文字以内で入力してください。',

            'isbn.required' => 'ISBNは必須です。',
            'isbn.digits' => 'ISBNは13桁で入力してください。',
            'isbn.unique' => 'このISBNは既に登録されています。',

            'published_date.required' => '出版日は必須です。',
            'published_date.date' => '出版日は有効な日付形式で入力してください。',

            'genres.required' => 'ジャンルは必須です。1つ以上選択してください。',
            'genres.array' => 'ジャンルは必須です。1つ以上選択してください。',
            'genres.min' => 'ジャンルは必須です。1つ以上選択してください。',

            'image_url.url' => '画像URLは有効なURL形式で入力してください。',
        ];
    }
}
