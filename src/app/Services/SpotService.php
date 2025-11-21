<?php

namespace App\Services;

use App\Models\Spot;
use Illuminate\Support\Facades\Auth;

class SpotService
{
   /**
    * 別のユーザーの場所を見られなくする為のメソッド。
    * @param $request
    * @return void
    */
   public static function checkUserSpot($request): void
   {
      // パラメーターを取得
      $id_spot = $request->route()->parameter('spot');
      // パラメーターが無ければチェック不要
      if (!is_null($id_spot)) {
         // 自分自身の場所なのかチェック
         $spot = Spot::select('user_id')->findOrFail($id_spot);
         if ($spot->user_id !== Auth::id()) {
            abort(404);
         }
      }
   }

   /**
    * 新しいスポットを保存するメソッド。
    * @param string $new_spot
    * @return Spot
    */
   public static function createSpot(string $new_spot): Spot
   {
      return Spot::create([
         'name' => $new_spot,
         'user_id' => Auth::id(),
      ]);
   }
}
