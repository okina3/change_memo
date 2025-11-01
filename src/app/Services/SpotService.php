<?php

namespace App\Services;

use App\Models\Memo;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;

class SpotService
{
   /**
    * 新規スポットの保存・更新するメソッド。
    * @param $request_new_spot
    * @param Memo $memo
    * @return void
    */
   public static function storeNewSpot($request_new_spot, Memo $memo): void
   {
      // 新規スポットの入力があった場合、スポットが重複していないか調べる
      $spot_exists = Spot::availableCheckDuplicateSpot($request_new_spot)->exists();
      // 新規スポットがあり、重複していなければ、スポットを保存、紐付け。
      if (!empty($request_new_spot) && !$spot_exists) {
         $spot = Spot::create([
            'name' => $request_new_spot,
            'user_id' => Auth::id()
         ]);
         //スポットとメモを紐付ける
         $spot->memos()->save($memo);
      }
   }
}
