<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(['key' => 'site_name'], ['value' => 'Field Forecast', 'type' => 'string']);
        Setting::firstOrCreate(['key' => 'contact_email'], ['value' => 'support@fieldforecasts.test', 'type' => 'string']);
        Setting::firstOrCreate(['key' => 'maintenance_mode'], ['value' => '0', 'type' => 'boolean']);
    }
}
