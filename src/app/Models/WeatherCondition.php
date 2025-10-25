<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherCondition extends Model
{
    use HasFactory;

    protected $table = 'weather_conditions';

    protected $fillable = [
        'memo_id',
        'weather_code',
        'wind_speed_min',
        'wind_speed_max',
        'wind_direction',
    ];

    protected $casts = [
        'wind_speed_min' => 'integer',
        'wind_speed_max' => 'integer',
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
