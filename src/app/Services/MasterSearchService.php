<?php

namespace App\Services;

use App\Models\Bait;
use App\Models\FishName;
use App\Models\Spot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class MasterSearchService
{
   /**
    * 別のユーザーのデータ（場所、エサ、魚名）を見られなくする為のメソッド。
    * @param mixed $request
    * @return void
    */
   public static function checkUserMaster($request): void
   {
      // パラメーターを取得
      $id = $request->route()->parameter('id');
      $type = $request->route()->parameter('type');

      // パラメーターが無ければチェック不要
      if (!is_null($id) && !is_null($type)) {
         // type をモデルクラスにマッピング
         $map = [
            'spot' => Spot::class,
            'bait' => Bait::class,
            'fish-name' => FishName::class,
         ];

         // マップからモデルクラスを取得（無効な `type` の場合は abort(404)）
         $class = $map[$type] ?? abort(404);
         // モデルクラスから、自分自身のレコードを取得
         $model = $class::select('user_id')->findOrFail($id);

         // 自分自身のデータ（場所、エサ、魚名）なのかチェック
         if (Schema::hasColumn($model->getTable(), 'user_id')) {
            // 認証ユーザーとレコードの所有者が異なる場合はアクセス拒否（404）
            if ($model->user_id !== Auth::id()) {
               abort(404);
            }
         }
      }
   }

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
