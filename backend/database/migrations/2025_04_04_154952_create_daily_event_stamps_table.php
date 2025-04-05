<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_event_stamps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('icon', 50);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 基本データの挿入
        DB::table('daily_event_stamps')->insert([
            [
                'name' => 'うんち',
                'icon' => '💩',
                'description' => '排便の記録',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '記念日',
                'icon' => '🎉',
                'description' => '成長の記念となる出来事',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '離乳食',
                'icon' => '🍼',
                'description' => '離乳食の開始や進展',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_event_stamps');
    }
};
