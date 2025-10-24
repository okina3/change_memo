<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weather_type_memo', function (Blueprint $table) {
            $table->foreignId('weather_type_id')->constrained('weather_types')->cascadeOnDelete();
            $table->foreignId('memo_id')->constrained('memos')->cascadeOnDelete();

            $table->primary(['weather_type_id', 'memo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_type_memo');
    }
};
