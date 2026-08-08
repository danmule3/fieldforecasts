<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'England' => 'ENG', 'Spain' => 'ESP', 'Italy' => 'ITA', 'Germany' => 'GER',
            'France' => 'FRA', 'Kenya' => 'KEN', 'Brazil' => 'BRA', 'United States' => 'USA',
        ];

        foreach ($countries as $name => $code) {
            Country::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'iso_code' => $code]
            );
        }
    }
}
