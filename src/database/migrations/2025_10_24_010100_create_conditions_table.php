<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')
                ->constrained('memos')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('wind_speed_min')
                ->nullable();
            $table->unsignedTinyInteger('wind_speed_max')
                ->nullable();
            $table->enum('wind_direction', ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'])
                ->nullable();
            $table->boolean('has_flow')
                ->nullable();
            $table->enum('water_clarity', ['clear', 'slightly', 'turbid', 'very_turbid'])
                ->nullable();
            $table->enum('underwater_debris', ['none', 'slightly', 'present'])
                ->nullable();
            $table->decimal('water_level', 5, 1)
                ->nullable();
            $table->unsignedTinyInteger('water_temp')
                ->nullable();
            $table->timestamps();
            $table->unique('memo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conditions');
    }
};
