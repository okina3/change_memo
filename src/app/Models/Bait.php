<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
