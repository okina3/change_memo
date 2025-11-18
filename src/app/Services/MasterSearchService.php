<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class MasterSearchService
{
   /**
    * モデルを `name` カラムで検索して、結果のコレクションを返します。
    *
    * 処理の流れ:
    * 1. 指定されたモデルクラスの新しいインスタンスを作成し、クエリビルダを取得します。
    * 2. モデルテーブルに `user_id` カラムが存在する場合は、現在ログイン中のユーザーで絞り込みます。
    * 3. `$keyword` が空でなければ `name` カラムに対して部分一致 (LIKE) 検索を行います。
    * 4. `id` 降順でソートして全件取得したコレクションを返します。
    *
    * @param string $class Eloquent モデルのクラス名（例: App\Models\Spot::class）
    * @param string|null $keyword 検索キーワード（部分一致）。null または空文字の場合は検索条件を適用しません。
    * @return \Illuminate\Database\Eloquent\Collection 検索結果のコレクション
    */
   public function search(string $class, ?string $keyword = null)
   {
      // モデルインスタンスとクエリビルダの取得
      $model = new $class;
      $query = $class::query();

      // user_id カラムがあるモデルの場合、ログインユーザーで絞り込む
      if (Schema::hasColumn($model->getTable(), 'user_id')) {
         $query->where('user_id', Auth::id());
      }

      // キーワードが指定されていれば name カラムに対して部分一致検索を追加
      if ($keyword !== null && $keyword !== '') {
         $query->where('name', 'like', "%{$keyword}%");
      }

      // 結果を id 降順で並び替え、全件を取得してコレクションで返す（ページネーションを行わない）
      return $query->orderBy('id', 'desc')->get();
   }
}
