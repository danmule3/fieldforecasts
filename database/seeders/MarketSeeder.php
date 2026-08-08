<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        $markets = [
            '1X2', 'Double Chance', 'Over 0.5', 'Over 1.5', 'Over 2.5', 'Over 3.5',
            'BTTS', 'Correct Score', 'Draw No Bet', 'Asian Handicap', 'Corners', 'Cards', 'Player Markets',
        ];

        foreach ($markets as $index => $name) {
            Market::firstOrCreate(
                ['key' => Str::slug($name, '_')],
                ['name' => $name, 'display_order' => $index, 'is_active' => true]
            );
        }
    }
}
