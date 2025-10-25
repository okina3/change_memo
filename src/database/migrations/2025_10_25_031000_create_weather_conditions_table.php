<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')
                ->constrained('memos')
                ->cascadeOnDelete();
            $table->enum('weather_code', [
                'sunny',
                'cloudy',
                'rain',
                'other',
            ])->nullable();
            $table->unsignedTinyInteger('wind_speed_min')->nullable();
            $table->unsignedTinyInteger('wind_speed_max')->nullable();
            $table->enum('wind_direction', ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'])->nullable();
            $table->timestamps();
            $table->unique('memo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_conditions');
    }
};
