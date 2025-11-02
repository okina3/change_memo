<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class SpotController extends Controller
{
    /**
     * 釣り場所を保存するメソッド。
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $spot = Spot::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);

        // ブラウザバック用フラッシュを次のリクエストに再度引き継ぐ。
        try {
            session()->reflash();
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }

        return response()->json([
            'id' => $spot->id,
            'name' => $spot->name,
        ], 201);
    }
}
