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
        Schema::create('river_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')
                ->constrained('memos')
                ->cascadeOnDelete();
            $table->boolean('has_flow');
            $table->enum('water_clarity', ['clear', 'slightly', 'turbid', 'very_turbid']);
            $table->enum('underwater_debris', ['none', 'slightly', 'present']);
            $table->decimal('water_level', 5, 1)->nullable();
            $table->unsignedTinyInteger('water_temp')->nullable();
            $table->timestamps();
            $table->unique('memo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('river_conditions');
    }
};
