<?php

namespace Database\Seeders;

use App\Models\Verify;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VerifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = ['is', 'is_not', 'regexp'];
        foreach ($array as $item) {
            Verify::create([
                'name' => $item,
            ]);
        }
    }
}
