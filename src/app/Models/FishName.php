<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FishName extends Model
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
        return $this->belongsToMany(Memo::class, 'memo_fish_names');
    }
}
