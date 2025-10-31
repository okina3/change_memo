<?php

namespace App\Services;

use App\Models\Bait;
use Illuminate\Support\Facades\Auth;

class BaitService
{
   /**
    * 新規エサの保存・更新するメソッド。
    * @param $request_new_bait
    * @param int $memo_id
    * @return void
    */
   public static function storeNewBait($request_new_bait, int $memo_id): void
   {
      // 新規エサの入力があった場合、エサが重複していないか調べる
      $bait_exists = Bait::availableCheckDuplicateBait($request_new_bait)->exists();
      // 新規エサがあり、重複していなければ、エサを保存し、中間テーブルに保存
      if (!empty($request_new_bait) && !$bait_exists) {
         // エサを保存
         $tag = Bait::create([
            'name' => $request_new_bait,
            'user_id' => Auth::id()
         ]);
         // メモとエサの中間テーブルに値を保存
         Bait::findOrFail($tag->id)->memos()->attach($memo_id);
      }
   }
}
