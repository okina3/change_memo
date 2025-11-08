<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class FishNameService
{
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
