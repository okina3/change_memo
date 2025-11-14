<?php

namespace App\Services;

use App\Models\FishName;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FishNameService
{
   /**
    * 新しい魚種を保存して返すメソッド。
    * @param string $new_fish_name
    * @return FishName
    */
   public static function storeFishName(string $new_fish_name): FishName
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
      $results = [];
      foreach ($select_memo_fish_names as $fish) {
         // メモにリレーションされた釣果のデータ（名前・匹数・長さ）を、配列に追加
         $results[] = [
            'name' => $fish->name,
            'count' => $fish->pivot->count ?? 0,
            'length' => $fish->pivot->length ?? 0,
         ];
      }
      return $results;
   }
}
