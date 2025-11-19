<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bait;
use App\Models\FishName;
use App\Models\Spot;
use App\Services\MasterSearchService;
use App\Services\SessionService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

class MasterController extends Controller
{
   /**
    * マスター管理画面（スポット / エサ / 魚名）
    * @return View
    */
   public function index(Request $request, MasterSearchService $searchService)
   {
      // ブラウザバック対策（値を削除する）
      SessionService::resetBrowserBackSession();

      // 表示するタブを取得
      $tab = $request->get('tab', 'spots');
      // 検索キーワードを取得
      $keyword = $request->get('keyword', '');
      // 各マスターデータを検索する
      $spots = $searchService->searchKeyword(Spot::class, $keyword);
      $baits = $searchService->searchKeyword(Bait::class, $keyword);
      $fishNames = $searchService->searchKeyword(FishName::class, $keyword);

      return view('user.masters.index', compact('tab', 'spots', 'baits', 'fishNames', 'keyword'));
   }

   /**
    * マスターデータの完全削除。
    * type: spot | bait | fish-name
    */
   public function destroy($type, $id)
   {
      /**
       * 受け取るタイプに応じてモデルクラスを決定するマップ
       * 将来的にモデルを追加する場合はここに追記する
       */
      $map = [
         'spot' => Spot::class,
         'bait' => Bait::class,
         'fish-name' => FishName::class,
      ];

      // 不正なタイプの場合は早期に 404 を返す
      if (!isset($map[$type])) {
         // フラッシュにも残しておく（ページ再読み込みで表示される）
         session()->flash('message', '不正なタイプです');
         session()->flash('status', 'alert');
         return response()->json(['message' => '不正なタイプです'], 404);
      }

      $class = $map[$type];

      // 指定 ID のレコードを取得（存在しない場合は 404）
      $model = $class::find($id);
      if (!$model) {
         session()->flash('message', '該当データが見つかりません');
         session()->flash('status', 'alert');
         return response()->json(['message' => '該当データが見つかりません'], 404);
      }

      // 所有チェック: テーブルに user_id カラムが存在する場合のみ現在ユーザーと照合
      if (Schema::hasColumn($model->getTable(), 'user_id') && $model->user_id !== Auth::id()) {
         session()->flash('message', '権限がありません');
         session()->flash('status', 'alert');
         return response()->json(['message' => '権限がありません'], 403);
      }

      try {
         // 削除を実行
         $model->delete();

         // 削除成功のメッセージをセッションにフラッシュする
         // ページ再読み込み時に `resources/views/components/common/flash-message.blade.php` が表示する
         session()->flash('message', '削除しました');
         session()->flash('status', 'alert');

         return response()->json(['ok' => true]);
      } catch (QueryException $e) {
         // 外部キー制約など関連データによる削除失敗
         Log::error('MasterController@destroy QueryException: ' . $e->getMessage());
         session()->flash('message', '関連データのため削除できません');
         session()->flash('status', 'alert');
         return response()->json(['message' => '関連データのため削除できません'], 409);
      } catch (Throwable $e) {
         // その他の予期せぬ例外
         Log::error('MasterController@destroy Throwable: ' . $e->getMessage());
         session()->flash('message', 'サーバーエラーが発生しました');
         session()->flash('status', 'alert');
         return response()->json(['message' => 'サーバーエラー'], 500);
      }
   }
}
