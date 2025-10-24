<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $table = 'conditions';

    protected $fillable = [
        'memo_id',
        'wind_speed_min',
        'wind_speed_max',
        'wind_direction',
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
        'wind_speed_min' => 'integer',
        'wind_speed_max' => 'integer',
    ];
}
