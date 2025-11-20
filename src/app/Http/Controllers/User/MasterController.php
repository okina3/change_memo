<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bait;
use App\Models\FishName;
use App\Models\Spot;
use App\Services\MasterSearchService;
use App\Services\SessionService;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

class MasterController extends Controller
{
   public function __construct()
   {
      // 別のユーザーのデータ（場所、エサ、魚名）を見られなくする認証。
      $this->middleware(function (Request $request, Closure $next) {
         MasterSearchService::checkUserMaster($request);
         return $next($request);
      });
   }
   /**
    * マスター管理画面（スポット/エサ/魚名）
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
      // type をモデルクラスにマッピング
      $map = [
         'spot' => Spot::class,
         'bait' => Bait::class,
         'fish-name' => FishName::class,
      ];

      // マップからモデルクラスを取得（無効な `type` の場合は abort(404)）
      $class = $map[$type] ?? abort(404);
      // モデルクラスから、レコードを取得
      $model = $class::findOrFail($id);

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
