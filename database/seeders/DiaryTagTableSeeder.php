<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiaryTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('diary_tag')->insert([
                'diary_id' => 1,
                'tag_id' => 1,
            ]);
        DB::table('diary_tag')->insert([
                'diary_id' => 1,
                'tag_id' => 2,
            ]);
    }
}
