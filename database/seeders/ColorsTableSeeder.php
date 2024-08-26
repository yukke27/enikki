<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
                'color_code_1' => '#FF5733',
                'color_code_2' => '#33FF57',
                'color_code_3' => '#3357FF',
            ]);
    }
}
