<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('spot_id')
                ->constrained('spots')
                ->restrictOnDelete();

            $table->date('fishing_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('weather', 50);
            $table->decimal('air_temp_c', 4, 1)->nullable();
            $table->decimal('max_wind_ms', 4, 1)->nullable();

            // 16方位コード: 0=N, 1=NNE, ..., 15=NNW
            $table->unsignedTinyInteger('wind_dir')->nullable()->comment('0..15');

            // アプリ側 enum コード（0,1,2...）
            $table->unsignedTinyInteger('river_flow')->nullable();
            $table->unsignedTinyInteger('turbidity')->nullable();
            $table->unsignedTinyInteger('debris')->nullable();

            $table->decimal('water_level_cm', 5, 1)->nullable(); // 負数あり得るが桁は十分
            $table->decimal('water_temp_c', 4, 1)->nullable();

            $table->text('content');
            //ソフトデリート
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
