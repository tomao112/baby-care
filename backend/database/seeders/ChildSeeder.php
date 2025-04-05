<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Child;
use Carbon\Carbon;

class ChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $children = [
            [
                'user_id' => 4,
                'name' => 'さくら',
                'birth_date' => Carbon::create(2018, 4, 1),
                'gender' => 'female',
                'notes' => 'お絵かきと歌が大好き'
            ],
            [
                'user_id' => 4,
                'name' => 'たろう',
                'birth_date' => Carbon::create(2019, 7, 15),
                'gender' => 'male',
                'notes' => '元気いっぱいなmale'
            ],
            [
                'user_id' => 4,
                'name' => 'ゆうき',
                'birth_date' => Carbon::create(2020, 1, 23),
                'gender' => 'male',
                'notes' => '恐竜が大好き'
            ],
            [
                'user_id' => 4,
                'name' => 'みお',
                'birth_date' => Carbon::create(2017, 12, 5),
                'gender' => 'female',
                'notes' => '本を読むのが好き'
            ],
            [
                'user_id' => 4,
                'name' => 'けんた',
                'birth_date' => Carbon::create(2021, 3, 8),
                'gender' => 'male',
                'notes' => 'サッカーが得意'
            ],
            [
                'user_id' => 4,
                'name' => 'あかり',
                'birth_date' => Carbon::create(2019, 9, 30),
                'gender' => 'female',
                'notes' => 'ピアノを習っている'
            ],
            [
                'user_id' => 4,
                'name' => 'そうた',
                'birth_date' => Carbon::create(2020, 6, 18),
                'gender' => 'male',
                'notes' => '虫取りが大好き'
            ],
            [
                'user_id' => 4,
                'name' => 'ひなた',
                'birth_date' => Carbon::create(2018, 8, 21),
                'gender' => 'female',
                'notes' => 'お料理のお手伝いが好き'
            ],
            [
                'user_id' => 4,
                'name' => 'りく',
                'birth_date' => Carbon::create(2021, 11, 3),
                'gender' => 'male',
                'notes' => 'いつも笑顔いっぱい'
            ],
            [
                'user_id' => 4,
                'name' => 'めい',
                'birth_date' => Carbon::create(2019, 2, 14),
                'gender' => 'female',
                'notes' => 'お絵かきが上手'
            ],
        ];

        foreach ($children as $child) {
            Child::create($child);
        }
    }
}
