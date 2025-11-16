<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBaitRequest extends FormRequest
{
   /**
    * @return bool
    */
   public function authorize(): bool
   {
      // users ガードで認証されていることを確認する
      return $this->user('users') !== null;
   }

   /**
    * リクエストに対するバリデーションルールを定義するメソッド。
    * @return string[]
    */
   public function rules(): array
   {
      return [
         'new_bait' => [
            'required',
            'string',
            'max:25',
            Rule::unique('baits', 'name')->where(function ($query) {
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
         'new_bait.required' => '新規エサを入力してください。',
         'new_bait.string' => '新規エサ名は文字列で入力してください。',
         'new_bait.max' => '新規エサは、25文字以内で入力してください。',
         'new_bait.unique' => 'このエサはすでに登録されています。',
      ];
   }
}
