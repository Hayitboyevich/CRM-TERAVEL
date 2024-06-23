<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('theme_settings')->insert([
            'panel' => 'superadmin',
            'header_color' => '#ed4040',
            'sidebar_color' => '#292929',
            'sidebar_text_color' => '#cbcbcb',
            'link_color' => '#ffffff',
            'created_at' => '2024-05-03 15:31:04',
            'updated_at' => '2024-05-03 15:31:04',
            'enable_rounded_theme' => 0,
            // Add other columns if needed
        ]);
    }
}
