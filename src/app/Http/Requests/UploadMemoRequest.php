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

    // /**
    //  * バリデーション前の入力整形（required_without と組み合わせて期待通り動作させるため）
    //  */
    // protected function prepareForValidation(): void
    // {
    //     // 生の入力値を取得
    //     $newSpotRaw = $this->input('new_spot');
    //     // 空白を削除（スペースだけ対策）。is_string チェックで、nullの場合に trim() を呼ばない為
    //     $newSpotTrimmed = is_string($newSpotRaw) ? trim($newSpotRaw) : $newSpotRaw;
    //     // 空文字列なら null に変換（required_withoutの誤判定防止）
    //     $this->merge([
    //         'new_spot' => $newSpotTrimmed === '' ? null : $newSpotTrimmed,
    //         'fishing_spot' => $this->input('fishing_spot') === '' ? null : $this->input('fishing_spot'),
    //     ]);
    // }

    /**
     * リクエストに対するバリデーションルールを定義するメソッド。
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'content'      => 'string|max:1000',
            'new_tag'      => 'nullable|max:25|unique:tags,name',
            // 'fishing_spot' => 'required_without:new_spot|nullable|integer|exists:spots,id',
            // 'new_spot'     => 'required_without:fishing_spot|nullable|string|max:25|unique:spots,name',
        ];
    }

    // /**
    //  * fishing_spot と new_spot の同時入力をチェック。
    //  *
    //  * @param ValidatorContract $validator
    //  * @return void
    //  */
    // public function withValidator(ValidatorContract $validator): void
    // {
    //     $validator->after(function (ValidatorContract $validator) {
    //         if ($this->filled('fishing_spot') && $this->filled('new_spot')) {
    //             // エラーは一箇所にまとめて表示するため、ここでは fishing_spot 側にのみ追加。
    //             $validator->errors()->add('fishing_spot', '場所は1箇所にしてください。');
    //         }
    //     });
    // }

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

            // fishing_spot 用メッセージ
            // 'fishing_spot.required_without' => '釣り場を選択するか、入力してください。',
            // 'fishing_spot.integer' => '場所は正しい選択肢から選んでください。',
            // 'fishing_spot.exists' => '選択された場所は存在しません。',
            // new_spot 用メッセージ
            // 'new_spot.required_without' => '釣り場を選択するか、入力してください。',
            // 'new_spot.unique' => 'この場所名は、すでに登録されています。',
            // 'new_spot.max' => '場所名は、25文字以内で入力してください。',
            // 'new_spot.string' => '新しい場所は文字列で入力してください。',
        ];
    }
}
