<?php

namespace App\Services;

use App\Models\Memo;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpotService
{
   /**
    * 新しいスポットを保存して返すメソッド。
    * @param string $new_spot
    * @return Spot
    */
   public static function storeSpot(string $new_spot): Spot
   {
      return DB::transaction(function () use ($new_spot) {
         return Spot::create([
            'name' => $new_spot,
            'user_id' => Auth::id(),
         ]);
      }, 10);
   }
}
