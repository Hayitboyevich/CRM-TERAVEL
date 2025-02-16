<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = ['text', 'phone'];
        foreach ($array as $item) {

            Condition::create([
                'social_network_id' => 1,
                'name'=> $item,
            ]);
        }
    }
}
