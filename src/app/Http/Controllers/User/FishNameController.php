<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreFishRequest;
use App\Models\FishName;
use App\Services\FishNameService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class FishNameController extends Controller
{
   /**
    * 新規魚種を保存するメソッド
    * @param StoreFishRequest $request
    * @return JsonResponse
    * @throws Throwable
    */
   public function store(StoreFishRequest $request): JsonResponse
   {
      try {
         $fish = FishNameService::createFishName($request->input('new_fish_name'));

         return response()->json([
            'id' => $fish->id,
            'name' => $fish->name,
         ], 201);
      } catch (Throwable $e) {
         Log::error($e);
         throw $e;
      }
   }

   /**
    * 指定の魚種を削除するメソッド。
    * @param Request $request
    * @return RedirectResponse
    */
   public function destroy(Request $request): RedirectResponse
   {
      $fishNameId = $request->input('fishNameId');
      $fishName = FishName::findOrFail($fishNameId);

      // 所有チェック
      if (Schema::hasColumn($fishName->getTable(), 'user_id') && $fishName->user_id !== Auth::id()) {
         return redirect()->back()->with('message', '権限がありません')->with('status', 'alert');
      }

      try {
         $fishName->delete();
         return redirect()->back()->with('message', '魚名を削除しました')->with('status', 'alert');
      } catch (QueryException $e) {
         Log::error('FishNameController@destroy QueryException: ' . $e->getMessage());
         return redirect()->back()->with('message', '関連データのため削除できません')->with('status', 'alert');
      } catch (Throwable $e) {
         Log::error('FishNameController@destroy Throwable: ' . $e->getMessage());
         return redirect()->back()->with('message', 'サーバーエラーが発生しました')->with('status', 'alert');
      }
   }
}
