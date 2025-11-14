<?php

namespace App\Services;

use App\Models\Bait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaitService
{
   /**
    * 新しいエサを保存して返すメソッド。
    * @param string $new_bait
    * @return Bait
    */
   public static function storeBait(string $new_bait): Bait
   {
      return DB::transaction(function () use ($new_bait) {
         return Bait::create([
            'name' => $new_bait,
            'user_id' => Auth::id(),
         ]);
      }, 10);
   }

   /**
    * 選択したメモに紐づいた、エサのNameを、配列で取得するメソッド。
    * @param Collection $select_memo_baits
    * @return array
    */
   public static function getMemoBaitsName(Collection $select_memo_baits): array
   {
      return $select_memo_baits->pluck('name')->toArray();
   }
}
