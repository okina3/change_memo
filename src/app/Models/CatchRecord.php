<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatchRecord extends Model
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

   /**
    * Memoモデルへのリレーションを返す（一対多）。
    * @return BelongsTo
    */
   public function memo(): BelongsTo
   {
      return $this->belongsTo(Memo::class);
   }
}
