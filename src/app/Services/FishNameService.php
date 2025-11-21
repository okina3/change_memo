<?php

namespace App\Services;

use App\Models\FishName;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FishNameService
{
   /**
    * 新しい魚種を保存するメソッド。
    * @param string $new_fish_name
    * @return FishName
    */
   public static function createFishName(string $new_fish_name): FishName
   {
      return DB::transaction(function () use ($new_fish_name) {
         return FishName::create([
            'name' => $new_fish_name,
            'user_id' => Auth::id(),
         ]);
      }, 10);
   }

   /**
    * 選択したメモに紐づいた釣果のデータ（名前・匹数・長さ）を配列で取得するメソッド。
    * @param Collection $select_memo_fish_names
    * @return array
    */
   public static function getMemoFishResults(Collection $select_memo_fish_names): array
   {
      return $select_memo_fish_names->map(function ($fish) {
         return [
            'name' => $fish->name,
            'count' => $fish->pivot->count ?? 0,
            'length' => $fish->pivot->length ?? 0,
         ];
      })->toArray();
   }
}
