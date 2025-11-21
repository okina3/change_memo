<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreBaitRequest;
use App\Models\Bait;
use App\Services\BaitService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class BaitController extends Controller
{
   public function __construct()
   {
      // 別のユーザーのエサを見られなくする認証。
      $this->middleware(function (Request $request, Closure $next) {
         BaitService::checkUserBait($request);
         return $next($request);
      });
   }

   /**
    * 新規エサを保存するメソッド。
    * @param StoreBaitRequest $request
    * @return JsonResponse
    * @throws Throwable
    */
   public function store(StoreBaitRequest $request): JsonResponse
   {
      try {
         $bait = BaitService::createBait($request->input('new_bait'));

         return response()->json([
            'id' => $bait->id,
            'name' => $bait->name,
         ], 201);
      } catch (Throwable $e) {
         Log::error($e);
         return response()->json([
            'message' => 'エサの登録に失敗しました。',
            'status' => 'alert'
         ], 500);
      }
   }

   /**
    * 指定のエサを削除するメソッド。
    * @param Request $request
    * @return RedirectResponse
    */
   public function destroy(Request $request): RedirectResponse
   {
      try {
         // 指定のエサを取得
         $bait = Bait::availableSelectBait($request->baitId)->first();

         // 多対多との関連がある場合
         if ($bait->memos()->exists()) {
            return redirect()->back()->with(['message' => '関連データのため削除できません。', 'status' => 'alert']);
         }
         // 選択したエサを削除
         $bait->delete();
         return redirect()->back()->with(['message' => 'エサを削除しました。', 'status' => 'alert']);
      } catch (Throwable $e) {
         Log::error($e);
         return redirect()->back()->with(['message' => 'エサの削除に失敗しました。', 'status' => 'alert']);
      }
   }
}
