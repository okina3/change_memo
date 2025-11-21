<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreSpotRequest;
use App\Models\Spot;
use App\Services\SpotService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SpotController extends Controller
{
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
            throw $e;
        }
    }

    /**
     * 指定の釣り場を削除するメソッド。
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $spotId = $request->input('spotId');
        $spot = Spot::findOrFail($spotId);

        // 所有チェック
        if (Schema::hasColumn($spot->getTable(), 'user_id') && $spot->user_id !== Auth::id()) {
            return redirect()->back()->with('message', '権限がありません')->with('status', 'alert');
        }

        try {
            $spot->delete();
            return redirect()->back()->with('message', '場所を削除しました')->with('status', 'alert');
        } catch (QueryException $e) {
            Log::error('SpotController@destroy QueryException: ' . $e->getMessage());
            return redirect()->back()->with('message', '関連データのため削除できません')->with('status', 'alert');
        } catch (Throwable $e) {
            Log::error('SpotController@destroy Throwable: ' . $e->getMessage());
            return redirect()->back()->with('message', 'サーバーエラーが発生しました')->with('status', 'alert');
        }
    }
}
