<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreBaitRequest;
use App\Http\Requests\User\StoreFishRequest;
use App\Http\Requests\User\StoreSpotRequest;
use App\Models\Bait;
use App\Models\FishName;
use App\Models\Spot;
use App\Services\BaitService;
use App\Services\FishNameService;
use App\Services\MasterService;
use App\Services\SessionService;
use App\Services\SpotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MasterController extends Controller
{
   /**
    * スポット/エサ/魚名（マスター管理画面）を一覧表示するメソッド。
    * @param Request $request
    * @param MasterService $searchService
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

   /**
    * マスター管理画面から場所を保存するメソッド。
    * @param StoreSpotRequest $request
    * @return RedirectResponse
    */
   public function storeSpot(StoreSpotRequest $request): RedirectResponse
   {
      try {
         $spot = SpotService::createSpot($request->input('new_spot'));
         return to_route('user.masters.index')->with('message', '場所を追加しました')->with('status', 'info');
      } catch (\Throwable $e) {
         Log::error('MasterController@storeSpot Throwable: ' . $e->getMessage());
         return back()->with('message', '場所の追加に失敗しました')->with('status', 'alert');
      }
   }

   /**
    * マスター管理画面からエサを保存するメソッド。
    * @param StoreBaitRequest $request
    * @return RedirectResponse
    */
   public function storeBait(StoreBaitRequest $request): RedirectResponse
   {
      try {
         $spot = BaitService::createBait($request->input('new_bait'));
         return to_route('user.masters.index')->with('message', 'エサを追加しました')->with('status', 'info');
      } catch (\Throwable $e) {
         Log::error('MasterController@storeSpot Throwable: ' . $e->getMessage());
         return back()->with('message', 'エサの追加に失敗しました')->with('status', 'alert');
      }
   }

   /**
    * マスター管理画面から魚名を保存するメソッド。
    * @param StoreFishRequest $request
    * @return RedirectResponse
    */
   public function storeFishName(StoreFishRequest $request): RedirectResponse
   {
      try {
         $spot = FishNameService::createFishName($request->input('new_fish_name'));
         return to_route('user.masters.index')->with('message', '魚名を追加しました')->with('status', 'info');
      } catch (\Throwable $e) {
         Log::error('MasterController@storeSpot Throwable: ' . $e->getMessage());
         return back()->with('message', '魚名の追加に失敗しました')->with('status', 'alert');
      }
   }
}
