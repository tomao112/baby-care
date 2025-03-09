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
        Schema::create('children', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 親ユーザーへの外部キー
            $table->string('name'); // 子どもの名前
            $table->date('birth_date'); // 生年月日
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // 性別
            $table->text('notes')->nullable(); // メモ
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
