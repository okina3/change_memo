<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bait extends Model
{
    use HasFactory;

    protected $table = 'baits';

    protected $fillable = [
        'memo_id',
        'name',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
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
