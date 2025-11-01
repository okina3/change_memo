<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Services\SessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SpotController extends Controller
{
    /**
     * メモの新規作成画面を表示するメソッド。
     * @return View
     */
    public function create(): View
    {
        // 全スポットを取得する
        // $all_spots = Spot::where('user_id', Auth::id())->get();

        // ブラウザバック対策（値を持たせる）
        SessionService::setBrowserBackSession();

        return view('user.spots.create', );
    }
}
