<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreFishRequest;
use App\Services\FishNameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
         $fish = FishNameService::storeFishName($request->input('new_fish_name'));

         return response()->json([
            'id' => $fish->id,
            'name' => $fish->name,
         ], 201);
      } catch (Throwable $e) {
         Log::error($e);
         throw $e;
      }
   }
}
