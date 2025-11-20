<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bait;
use App\Models\FishName;
use App\Models\Spot;
use App\Services\MasterService;
use App\Services\SessionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterController extends Controller
{
   /**
    * マスター管理画面（スポット/エサ/魚名）
    * @return View
    */
   public function index(Request $request, MasterService $searchService)
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
}
