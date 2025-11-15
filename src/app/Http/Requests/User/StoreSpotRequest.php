<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpotRequest extends FormRequest
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
         'new_spot' => [
            'required',
            'string',
            'max:25',
            Rule::unique('spots', 'name')->where(function ($query) {
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
         'new_spot.required' => '新規釣り場を入力してください。',
         'new_spot.string' => '新規釣り場名は文字列で入力してください。',
         'new_spot.max' => '新規釣り場は、25文字以内で入力してください。',
         'new_spot.unique' => 'この場所はすでに登録されています。',
      ];
   }
}
