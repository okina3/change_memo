<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class MasterService
{
   /**
    * 検索した各項目の名前を表示する為のメソッド。
    * @param string $class 
    * @param string|null $keyword 
    * @return Collection 
    */
   public function searchKeyword(string $class, ?string $keyword = null)
   {
      // 各指定モデルのインスタンスを生成、クエリビルダの取得
      $model = new $class;
      $query = $class::query();

      // 現在ログイン中ユーザーのデータに絞り込む
      if (Schema::hasColumn($model->getTable(), 'user_id')) {
         $query->where('user_id', Auth::id());
      }

      // キーワードが指定されていれば検索をする
      if ($keyword !== null && $keyword !== '') {
         // 全角スペースを半角に変換
         $spaceConvert = mb_convert_kana($keyword, 's');
         // 空白で分割して単語配列にする
         $keywords = preg_split('/\s+/', $spaceConvert, -1, PREG_SPLIT_NO_EMPTY) ?: [];
         // 各単語ごとに OR 条件で、各name カラムを部分一致検索
         $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
               $q->orWhere('name', 'like', '%' . $word . '%');
            }
         });
      }

      // 結果を id 降順で並び替え、全件を取得してコレクションで返す
      return $query->orderBy('id', 'desc')->get();
   }
}
