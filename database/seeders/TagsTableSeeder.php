<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insert([
                'user_id' => 1,
                'name' => '京都',
            ]);
        DB::table('tags')->insert([
                'user_id' => 1,
                'name' => '観光',
            ]);
    }
}
