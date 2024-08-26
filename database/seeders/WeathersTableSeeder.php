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
                'icon_path' => 'images/weathers/sunny_24dp_000000_FILL0_wght100_GRAD-25_opsz24.png',
            ]);
        DB::table('weathers')->insert([
                'name' => 'cloudy',
                'icon_path' => 'images/weathers/cloud_24dp_000000_FILL0_wght100_GRAD-25_opsz24.png',
            ]);
        DB::table('weathers')->insert([
                'name' => 'rainy',
                'icon_path' => 'images/weathers/rainy_24dp_000000_FILL0_wght100_GRAD-25_opsz24.png',
            ]);
        DB::table('weathers')->insert([
                'name' => 'snowy',
                'icon_path' => 'images/weathers/weather_snowy_24dp_000000_FILL0_wght100_GRAD-25_opsz24.png',
            ]);
    }
}
