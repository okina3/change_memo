<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('baits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')
            ->constrained('memos')
            ->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('position')
            ->default(0);
            $table->timestamps();

            $table->unique(['memo_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baits');
    }
};
