<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreFishRequest;
use App\Models\FishName;
use App\Services\FishNameService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class FishNameController extends Controller
{
   public function __construct()
   {
      // 別のユーザーの魚名を見られなくする認証。
      $this->middleware(function (Request $request, Closure $next) {
         FishNameService::checkUserFishName($request);
         return $next($request);
      });
   }

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
         return response()->json([
            'message' => '魚名の登録に失敗しました。',
            'status' => 'alert'
         ], 500);
      }
   }

   /**
    * 指定の魚種を削除するメソッド。
    * @param Request $request
    * @return RedirectResponse
    */
   public function destroy(Request $request): RedirectResponse
   {
      try {
         // 指定のエサを取得
         $fish_name = FishName::availableSelectFishName($request->fishNameId)->first();

         // 多対多との関連がある場合
         if ($fish_name->memos()->exists()) {
            return redirect()->back()->with(['message' => '関連データのため削除できません。', 'status' => 'alert']);
         }
         // 選択した魚名を削除
         $fish_name->delete();
         return redirect()->back()->with('message', '正常に魚名を削除しました。')->with('status', 'info');
      } catch (Throwable $e) {
         Log::error($e);
         return redirect()->back()->with('message', '魚名の削除に失敗しました。')->with('status', 'alert');
      }
   }
}
