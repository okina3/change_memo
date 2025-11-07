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
            // 釣行日・時間・釣り場
            'fishing_date' => 'required|date|before_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'fishing_spot' => 'required|integer|exists:spots,id',
            // 釣果入力（配列）
            'fishing_results' => 'array',
            'fishing_results.*.fish_name' => 'required|integer|exists:fish_names,id',
            'fishing_results.*.count' => 'nullable|integer|min:0',
            'fishing_results.*.length' => 'nullable|integer|min:0',
            // 新規タグ
            'new_tag'      => [
                'nullable',
                'max:25',
                Rule::unique('tags', 'name')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
            // 備考
            'content'      => 'string|max:1000',
        ];
    }

    /**
     * バリデーションエラーメッセージを定義するメソッド。
     * @return string[]
     */
    public function messages(): array
    {
        return [
            // 釣行日・時間・釣り場
            'fishing_date.required' => '釣行日を指定してください。',
            'fishing_date.date' => '釣行日の形式が不正です。',
            'fishing_date.before_or_equal' => '釣行日は今日以前の日付を指定してください。',
            'start_time.date_format' => '開始時間の形式は HH:MM で指定してください。',
            'end_time.date_format' => '終了時間の形式は HH:MM で指定してください。',
            'end_time.after_or_equal' => '終了時間は開始時間以降を指定してください。',
            'fishing_spot.required' => '場所を選択してください。',
            'fishing_spot.integer' => '場所は整数で指定してください。',
            'fishing_spot.exists' => '選択された場所は存在しません。',

            // 釣果入力（配列）
            'fishing_results.array' => '釣果データの形式が不正です。',
            'fishing_results.*.fish_name.required' => '魚名を選択してください。',
            'fishing_results.*.fish_name.integer' => '魚名の値が不正です。',
            'fishing_results.*.fish_name.exists' => '選択された魚名は存在しません。',
            'fishing_results.*.count.integer' => '匹数は整数で指定してください。',
            'fishing_results.*.count.min' => '匹数は 0 以上で指定してください。',
            'fishing_results.*.length.integer' => '長さは整数で指定してください。',
            'fishing_results.*.length.min' => '長さは 0 以上で指定してください。',
            // 新規タグ
            'new_tag.max' => 'タグは、25文字以内で入力してください。',
            'new_tag.unique' => 'このタグは、すでに登録されています。',
            // 備考
            'content.string' => 'メモの備考が空です。また、文字列で指定してください。',
            'content.max' => '文字数は、1000文字以内にしてください。',
        ];
    }
}
