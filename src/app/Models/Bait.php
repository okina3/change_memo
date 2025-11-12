<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Bait extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Memoモデルとの多対多のリレーションを定義。
     * @return BelongsToMany
     */
    public function memos(): BelongsToMany
    {
        return $this->belongsToMany(Memo::class, 'memo_baits');
    }

    /**
     * Userモデルへのリレーションを返す（一対多）。
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 自分自身の、全てのエサを取得する為のスコープ。
     * @param Builder $query
     * @return void
     */
    public function scopeAvailableAllBaits(Builder $query): void
    {
        $query->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
    }
}
