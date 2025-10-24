<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatchItem extends Model
{
    use HasFactory;

    protected $table = 'catches';

    protected $fillable = [
        'memo_id',
        'name',
        'count',
        'length_cm',
        'position',
    ];

    protected $casts = [
        'count' => 'integer',
        'length_cm' => 'integer',
        'position' => 'integer',
    ];
}
