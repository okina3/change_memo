<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiverCondition extends Model
{
    use HasFactory;

    protected $table = 'river_conditions';

    protected $fillable = [
        'memo_id',
        'has_flow',
        'water_clarity',
        'underwater_debris',
        'water_level',
        'water_temp',
    ];

    protected $casts = [
        'has_flow' => 'boolean',
        'water_level' => 'decimal:1',
        'water_temp' => 'integer',
    ];

    /**
     * Memoモデルへのリレーションを返す（一対一）。
     * @return BelongsTo
     */
    public function memo(): BelongsTo
    {
        return $this->belongsTo(Memo::class);
    }
}
