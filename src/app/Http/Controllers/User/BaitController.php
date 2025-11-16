<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreBaitRequest;
use App\Services\BaitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class BaitController extends Controller
{
   /**
    * 新規エサを保存するメソッド。
    * @param StoreBaitRequest $request
    * @return JsonResponse
    */
   public function store(StoreBaitRequest $request): JsonResponse
   {
      try {
         $bait = BaitService::storeBait($request->input('new_bait'));

         return response()->json([
            'id' => $bait->id,
            'name' => $bait->name,
         ], 201);
      } catch (Throwable $e) {
         Log::error($e);
         throw $e;
      }
   }
}
