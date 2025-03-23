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
        Schema::create('growth_records', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade'); // 子どもへの外部キー childrenテーブルと紐づけ　onDelete('cascade')は子どもが削除されたら、それに紐づく成長記録も削除される
            $table->date('record_date'); // 記録日
            $table->decimal('height', 5, 2); // 身長
            $table->decimal('weight', 5, 2); // 体重
            $table->text('memo')->nullable(); // メモ
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_records');
    }
};
