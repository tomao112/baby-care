<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // データベース操作のためのファサード


class GrowthRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //     // 直接データを挿入する方法
        // DB::table('growth_records')->insert([
        //     'id' => 1,
        //     'child_id' => 6,
        //     'record_date' => now(),
        //     'height' => 75.5,
        //     'weight' => 9.2,
        //     'memo' => '健診で測定',
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
            // 10件のテストデータを作成
        \App\Models\GrowthRecord::factory()->count(10)->create();
    }
}
