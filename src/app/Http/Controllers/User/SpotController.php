<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreSpotRequest;
use App\Services\SpotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class SpotController extends Controller
{
    /**
     * 釣り場所を保存するメソッド。
     * @param StoreSpotRequest $request
     * @return JsonResponse
     */
    public function store(StoreSpotRequest $request): JsonResponse
    {
        try {
            $spot = SpotService::storeSpot($request->input('new_spot'));

            return response()->json([
                'id' => $spot->id,
                'name' => $spot->name,
            ], 201);
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }
    }
}
