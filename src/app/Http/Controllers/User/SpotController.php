<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreSpotRequest;
use App\Models\Spot;
use App\Services\SpotService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SpotController extends Controller
{
    public function __construct()
    {
        // 別のユーザーの釣り場を見られなくする認証。
        $this->middleware(function (Request $request, Closure $next) {
            SpotService::checkUserSpot($request);
            return $next($request);
        });
    }

    /**
     * 釣り場所を保存するメソッド。
     * @param StoreSpotRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreSpotRequest $request): JsonResponse
    {
        try {
            $spot = SpotService::createSpot($request->input('new_spot'));

            return response()->json([
                'id' => $spot->id,
                'name' => $spot->name,
            ], 201);
        } catch (Throwable $e) {
            Log::error($e);
            return response()->json([
                'message' => '場所の登録に失敗しました。',
                'status' => 'alert'
            ], 500);
        }
    }

    /**
     * 指定の釣り場を削除するメソッド。
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // 選択した釣り場を削除
            Spot::availableSelectSpot($request->spotId)->delete();
            return redirect()->back()->with('message', '正常に場所を削除しました')->with('status', 'info');
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->back()->with('message', '場所の削除に失敗しました。')->with('status', 'alert');
        }
    }
}
