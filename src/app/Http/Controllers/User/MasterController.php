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
   public function destroy(Request $request, $type, $id)
   {

      // 受け取るタイプに応じてモデルクラスを決定するマップ
      $map = [
         'spot' => Spot::class,
         'bait' => Bait::class,
         'fish-name' => FishName::class,
      ];

      // 不正なタイプはリダイレクトで通知
      if (!isset($map[$type])) {
         return redirect()->back()->with('message', '不正なタイプです')->with('status', 'alert');
      }

      $class = $map[$type];

      // 指定 ID のレコードを取得（存在しない場合はメッセージを返す）
      $model = $class::find($id);
      if (!$model) {
         return redirect()->back()->with('message', '該当データが見つかりません')->with('status', 'alert');
      }

      // 所有チェック（user_id カラムがある場合のみ）
      if (Schema::hasColumn($model->getTable(), 'user_id') && $model->user_id !== Auth::id()) {
         return redirect()->back()->with('message', '権限がありません')->with('status', 'alert');
      }

      try {
         $model->delete();
         return redirect()->back()->with('message', '削除しました')->with('status', 'alert');
      } catch (QueryException $e) {
         // 外部キー制約など関連データによる削除失敗
         Log::error('MasterController@destroy QueryException: ' . $e->getMessage());
         return redirect()->back()->with('message', '関連データのため削除できません')->with('status', 'alert');
      } catch (Throwable $e) {
         // その他の予期せぬ例外
         Log::error('MasterController@destroy Throwable: ' . $e->getMessage());
         return redirect()->back()->with('message', 'サーバーエラーが発生しました')->with('status', 'alert');
      }
   }
}
