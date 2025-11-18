<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bait;
use App\Models\FishName;
use App\Models\Spot;
use App\Services\SessionService;
use App\Services\MasterSearchService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class MasterController extends Controller
{
   /**
    * マスター管理画面（スポット / エサ / 魚種）
    */
   public function index(Request $request, MasterSearchService $searchService)
   {
      // ブラウザバック対策（値を削除する）
      SessionService::resetBrowserBackSession();

      $tab = $request->get('tab', 'spots');
      $keyword = $request->get('keyword', '');

      // Use MasterSearchService to build paginated results (action injection)
      $spots = $searchService->search(Spot::class, $keyword);
      $baits = $searchService->search(Bait::class, $keyword);
      $fishNames = $searchService->search(FishName::class, $keyword);

      return view('user.masters.index', compact('tab', 'spots', 'baits', 'fishNames', 'keyword'));
   }

   /**
    * 削除処理（完全削除）
    * type: spot | bait | fish-name
    */
   public function destroy($type, $id)
   {
      $map = [
         'spot' => Spot::class,
         'bait' => Bait::class,
         'fish-name' => FishName::class,
      ];

      if (!isset($map[$type])) {
         return response()->json(['message' => '不正なタイプです'], 404);
      }

      $class = $map[$type];
      $model = $class::find($id);
      if (!$model) {
         return response()->json(['message' => '該当データが見つかりません'], 404);
      }

      // 所有チェック（user_id カラムがある場合のみ）
      if (Schema::hasColumn($model->getTable(), 'user_id') && $model->user_id !== Auth::id()) {
         return response()->json(['message' => '権限がありません'], 403);
      }

      try {
         $model->delete();
         return response()->json(['ok' => true]);
      } catch (QueryException $e) {
         Log::error($e->getMessage());
         return response()->json(['message' => '関連データのため削除できません'], 409);
      } catch (Throwable $e) {
         Log::error($e->getMessage());
         return response()->json(['message' => 'サーバーエラー'], 500);
      }
   }
}
