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

            // 釣行日時
            $table->date('fishing_date');
            $table->time('start_time');
            $table->time('end_time');

            // 天候・気象
            $table->string('weather', 15);
            $table->decimal('air_temp', 3, 1)->nullable();
            $table->decimal('max_wind', 3, 1)->nullable();
            $table->string('wind_dir', 2)->nullable();

            // 川の状態
            $table->unsignedTinyInteger('river_flow');
            $table->unsignedTinyInteger('turbidity');
            $table->unsignedTinyInteger('debris');
            $table->decimal('water_level', 4, 1)->nullable();
            $table->decimal('water_temp', 3, 1)->nullable();

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
