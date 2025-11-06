<?php

namespace App\Services;

use App\Models\Bait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class BaitService
{
   /**
    * 選択したメモに紐づいた、エサのNameを、配列で取得するメソッド。
    * @param Collection $select_memo_baits
    * @return array
    */
   public static function getMemoBaitsName(Collection $select_memo_baits): array
   {
      $memo_relation_baits_name = [];
      foreach ($select_memo_baits as $memo_relation_bait) {
         // メモにリレーションされたタグのidを、配列に追加
         $memo_relation_baits_name[] = $memo_relation_bait->name;
      }
      return $memo_relation_baits_name;
   }
}
