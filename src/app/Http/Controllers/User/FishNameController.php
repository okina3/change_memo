<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFishRequest;
use App\Models\FishName;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FishNameController extends Controller
{
   /**
    * 新規魚種を保存する
    * @param StoreFishRequest $request
    * @return JsonResponse
    */
   public function store(StoreFishRequest $request): JsonResponse
   {
      try {
         $fish = DB::transaction(function () use ($request) {
            return FishName::create([
               'name' => $request->input('new_fish'),
               'user_id' => Auth::id(),
            ]);
         }, 10);

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
