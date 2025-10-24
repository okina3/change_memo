<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('memos', function (Blueprint $table) {
            if (!Schema::hasColumn('memos', 'fishing_date')) {
                $table->date('fishing_date')->nullable()->after('id');
            }
            if (!Schema::hasColumn('memos', 'fishing_time_start')) {
                $table->time('fishing_time_start')->nullable()->after('fishing_date');
            }
            if (!Schema::hasColumn('memos', 'fishing_time_end')) {
                $table->time('fishing_time_end')->nullable()->after('fishing_time_start');
            }
            if (!Schema::hasColumn('memos', 'fishing_spot')) {
                $table->string('fishing_spot', 120)->nullable()->after('fishing_time_end');
            }
            if (!Schema::hasColumn('memos', 'content')) {
                $table->text('content')->nullable()->after('fishing_spot');
            }
        });

        // 旧構成の不要カラムが万一存在する場合は落とす（存在チェック付き）
        Schema::table('memos', function (Blueprint $table) {
            $dropCols = [
                'title',
                'wind_speed_min', 'wind_speed_max', 'wind_direction',
                'has_flow', 'water_clarity', 'underwater_debris',
                'water_level', 'water_temp',
                'weather',
                'bait',
            ];
            $existing = array_filter($dropCols, fn($c) => Schema::hasColumn('memos', $c));
            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }

    public function down(): void
    {
        Schema::table('memos', function (Blueprint $table) {
            $addCols = ['fishing_date','fishing_time_start','fishing_time_end','fishing_spot','content'];
            $existing = array_filter($addCols, fn($c) => Schema::hasColumn('memos', $c));
            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};
