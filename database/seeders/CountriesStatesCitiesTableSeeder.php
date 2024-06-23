<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountriesStatesCitiesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = File::get(database_path('create_countries_table.sql'));
        DB::unprepared($sql);

        $sql = File::get(database_path('states.sql'));
        DB::unprepared($sql);

        $sql = File::get(database_path('cities.sql'));
        DB::unprepared($sql);
    }

}
