<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpotRequest;
use App\Models\Spot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            DB::transaction(function () use ($request, &$spot) {
                $spot = Spot::create([
                    'name' => $request->name,
                    'user_id' => Auth::id(),
                ]);
            }, 10);

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
