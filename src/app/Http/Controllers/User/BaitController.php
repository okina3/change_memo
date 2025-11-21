<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreBaitRequest;
use App\Models\Bait;
use App\Services\BaitService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class BaitController extends Controller
{
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
      $baitId = $request->input('baitId');
      $bait = Bait::findOrFail($baitId);

      // 所有チェック
      if (Schema::hasColumn($bait->getTable(), 'user_id') && $bait->user_id !== Auth::id()) {
         return redirect()->back()->with('message', '権限がありません')->with('status', 'alert');
      }

      try {
         $bait->delete();
         return redirect()->back()->with('message', 'エサを削除しました')->with('status', 'alert');
      } catch (QueryException $e) {
         Log::error($e);
         return redirect()->back()->with('message', '関連データのため削除できません')->with('status', 'alert');
      } catch (Throwable $e) {
         Log::error($e);
         return redirect()->back()->with('message', 'サーバーエラーが発生しました')->with('status', 'alert');
      }
   }
}
