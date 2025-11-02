<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

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
            'content'      => 'string|max:1000',
            'new_tag'      => 'nullable|max:25|unique:tags,name',
            'fishing_spot' => 'required|exists:spots,id',
            'new_spot'     => 'nullable|string|max:25|unique:spots,name',
        ];
    }

    /**
     * バリデーションエラーメッセージを定義するメソッド。
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'new_tag.max' => 'タグは、25文字以内で入力してください。',
            'new_tag.unique' => 'このタグは、すでに登録されています。',
            'content.string' => 'メモの備考が空です。また、文字列で指定してください。',
            'content.max' => '文字数は、1000文字以内にしてください。',
            'fishing_spot.required' => '場所を選択してください。',
            'fishing_spot.exists' => '選択された場所は存在しません。',
            'new_spot.string' => '新規の場所は文字列で入力してください。',
            'new_spot.max' => '場所名は、25文字以内で入力してください。',
            'new_spot.unique' => 'この場所はすでに登録されています。',
        ];
    }
}
