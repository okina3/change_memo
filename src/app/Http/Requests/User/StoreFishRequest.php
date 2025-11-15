<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFishRequest extends FormRequest
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
         'new_fish_name' => [
            'required',
            'string',
            'max:25',
            Rule::unique('fish_names', 'name')->where(function ($query) {
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
         'new_fish_name.required' => '魚種名を入力してください。',
         'new_fish_name.string' => '魚種名は文字列で入力してください。',
         'new_fish_name.max' => '魚種名は、25文字以内で入力してください。',
         'new_fish_name.unique' => 'この魚種はすでに登録されています。',
      ];
   }
}
