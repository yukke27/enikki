<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('templates')->insert([
                'name' => '1-1',
                'icon_path' => 'images/templates/1-1.svg',
            ]);
        DB::table('templates')->insert([
                'name' => '16-9',
                'icon_path' => 'images/templates/16-9.svg',
            ]);
    }
}
