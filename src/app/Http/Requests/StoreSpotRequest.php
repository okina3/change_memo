<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpotRequest extends FormRequest
{
   /**
    * @return bool
    */
   public function authorize(): bool
   {
      // 新規釣り場の追加はログインユーザーのみ許可
      return auth()->check();
   }

   /**
    * リクエストに対するバリデーションルールを定義するメソッド。
    * @return string[]
    */
   public function rules(): array
   {
      return [
         'name' => 'required|string|max:25|unique:spots,name',
      ];
   }

   /**
    * バリデーションエラーメッセージを定義するメソッド。
    * @return string[]
    */
   public function messages(): array
   {
      return [
         'name.required' => '新規釣り場を入力してください。',
         'name.string' => '新規釣り場名は文字列で入力してください。',
         'name.max' => '新規釣り場は、25文字以内で入力してください。',
         'name.unique' => 'この場所はすでに登録されています。',
      ];
   }
}
