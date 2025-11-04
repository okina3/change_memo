<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBaitRequest;
use App\Models\Bait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
         $bait = DB::transaction(function () use ($request) {
            return Bait::create([
               'name' => $request->input('new_bait'),
               'user_id' => Auth::id(),
            ]);
         }, 10);

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
