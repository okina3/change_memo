<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMemoRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * リクエストに対するバリデーションルールを定義するメソッド。
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'title' => 'string|max:25',
            'content' => 'string|max:1000',
            'new_tag' => 'nullable|max:25|unique:tags,name',
        ];
    }

    /**
     * バリデーション前に、weather を配列へ正規化する。
     * 
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('weather')) {
            $weather = $this->input('weather');
            if (is_string($weather)) {
                // 空文字は空配列へ、それ以外は単一要素配列へ
                $weather = trim($weather) === '' ? [] : [$weather];
            } elseif ($weather === null) {
                $weather = [];
            }
            $this->merge([
                'weather' => $weather,
            ]);
        }
    }

    /**
     * バリデーションエラーメッセージを定義するメソッド。
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'title.string' => 'タイトルが空です。また、文字列で指定してください。',
            'title.max' => 'タイトルは、25文字以内で入力してください。',
            'content.string' => 'メモの内容が空です。また、文字列で指定してください。',
            'content.max' => '文字数は、1000文字以内にしてください。',
            'new_tag.max' => 'タグは、25文字以内で入力してください。',
            'new_tag.unique' => 'このタグは、すでに登録されています。',
        ];
    }
}
