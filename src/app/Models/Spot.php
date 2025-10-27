<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Memoモデルへのリレーションを返す（一対多）。
     * @return BelongsTo
     */
    public function memo(): BelongsTo
    {
        return $this->belongsTo(Memo::class);
    }
}
