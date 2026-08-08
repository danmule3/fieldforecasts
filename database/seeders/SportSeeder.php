<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $sports = ['Football', 'Basketball', 'Tennis', 'Rugby', 'Cricket', 'Esports', 'Others'];

        foreach ($sports as $index => $name) {
            Sport::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'display_order' => $index, 'is_active' => true]
            );
        }
    }
}
