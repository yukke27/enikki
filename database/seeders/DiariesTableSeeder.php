<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('diaries')->insert([
                'user_id' => 1,
                'weather_id' => 1,
                'color_id' => 1,
                'title' => 'title',
                'body' => 'this is body',
                'image_url' => 'images/test.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
    }
}
