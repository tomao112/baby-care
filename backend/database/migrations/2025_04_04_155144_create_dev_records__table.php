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
        Schema::create('dev_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('daily_event_stamps_id')
                ->constrained()
                ->onDelete('restrict');
            $table->date('record_date');
            $table->time('record_time')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            // インデックスの追加
            $table->index(['child_id', 'record_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dev_records');
    }
};
