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
      // new_spot に値がある場合は、既存スポットを探してあればそれを使い、なければ新規作成して
      // 常にメモの紐付け先を上書きする（spot_id を設定して保存する）。
      if (empty($request_new_spot)) {
         return;
      }

      // 重複チェック — マッチする既存スポットがあれば取得
      $existingSpot = Spot::availableCheckDuplicateSpot($request_new_spot)->first();

      if ($existingSpot) {
         // 既存スポットが見つかったらそのスポットに紐付け（上書き）
         $memo->spot()->associate($existingSpot);
         $memo->save();
         return;
      }

      // 重複が無ければ新規作成して紐付け
      $spot = Spot::create([
         'name' => $request_new_spot,
         'user_id' => Auth::id(),
      ]);

      // 明示的にメモの spot_id を上書きして保存
      $memo->spot()->associate($spot);
      $memo->save();
   }
}
