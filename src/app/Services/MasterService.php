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
   public function searchKeyword(string $class, ?string $keyword = null): Collection
   {
      // 各指定モデルのインスタンスを生成、クエリビルダの取得
      $model = new $class;
      $query = $class::query();

      // 現在ログイン中ユーザーのデータに絞り込む
      if (Schema::hasColumn($model->getTable(), 'user_id')) {
         $query->where('user_id', Auth::id());
      }

      // キーワードが指定されていれば、モデル側のスコープを使って検索する
      if ($keyword !== null && $keyword !== '') {
         if (method_exists($model, 'scopeSearchKeyword')) {
            $query->searchKeyword($keyword);
         }
      }

      // 結果を id 降順で並び替え、全件を取得してコレクションで返す
      return $query->orderBy('id', 'desc')->get();
   }
}
