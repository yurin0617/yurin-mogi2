<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
        return [
            // 評価（星の数）は必須、1〜5の整数であること
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            // コメントは必須、文字列であること
            'comment' => ['required', 'string', 'max:1000'],
        ];
    }
}
