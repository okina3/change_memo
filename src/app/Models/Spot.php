<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Memoモデルとの一対多のリレーションを定義。
     * @return HasMany
     */
    public function memos(): HasMany
    {
        return $this->hasMany(Memo::class);
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
     * 自分自身の、全ての釣り場を取得する為のスコープ。
     * @param Builder $query
     * @return void
     */
    public function scopeAvailableAllSpots(Builder $query): void
    {
        $query->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
    }

    /**
     * 自分自身の、選択した釣り場を取得する為のスコープ。
     * @param Builder $query
     * @param int $id
     * @return void
     */
    public function scopeAvailableSelectSpot(Builder $query, int $id): void
    {
        $query->where('id', $id)
            ->where('user_id', Auth::id());
    }
}
