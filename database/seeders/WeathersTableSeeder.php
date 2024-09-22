<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeathersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('weathers')->insert([
                'name' => 'sunny',
                'icon_path' => 'images/weathers/sunny.svg',
            ]);
        DB::table('weathers')->insert([
                'name' => 'partly_cloudy',
                'icon_path' => 'images/weathers/partly_cloudy.svg',
            ]);
        DB::table('weathers')->insert([
                'name' => 'cloudy',
                'icon_path' => 'images/weathers/cloudy.svg',
            ]);
        DB::table('weathers')->insert([
                'name' => 'rainy',
                'icon_path' => 'images/weathers/rainy.svg',
            ]);
        DB::table('weathers')->insert([
                'name' => 'snowy',
                'icon_path' => 'images/weathers/snowy.svg',
            ]);
    }
}
