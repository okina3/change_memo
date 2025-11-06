<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'fishing_spot' => 'required|exists:spots,id',
            // 釣果入力（配列）
            'fishing_results' => 'array',
            'fishing_results.*.fish_name' => 'required|integer|exists:fish_names,id',
            'fishing_results.*.count' => 'nullable|integer|min:0',
            'fishing_results.*.length' => 'nullable|integer|min:0',
            'new_tag'      => [
                'nullable',
                'max:25',
                Rule::unique('tags', 'name')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
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
            // fishing_results
            'fishing_results.array' => '釣果データの形式が不正です。',
            'fishing_results.*.fish_name.required' => '魚名を選択してください。',
            'fishing_results.*.fish_name.integer' => '魚名の値が不正です。',
            'fishing_results.*.fish_name.exists' => '選択された魚名は存在しません。',
            'fishing_results.*.count.integer' => '匹数は整数で指定してください。',
            'fishing_results.*.count.min' => '匹数は 0 以上で指定してください。',
            'fishing_results.*.length.integer' => '長さは整数で指定してください。',
            'fishing_results.*.length.min' => '長さは 0 以上で指定してください。',
        ];
    }
}
