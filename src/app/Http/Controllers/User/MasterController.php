<?php

namespace App\Http\Controllers\User;

/**
 * 互換性のための薄いラッパー。
 * 既存コードで `MasterController` を参照している箇所が残っている場合に
 * 新しい `MastersController` を継承して動作させます。
 */
class MasterController extends MastersController
{
    // Deprecated shim
}
