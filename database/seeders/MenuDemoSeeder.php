<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuDemoSeeder extends Seeder
{
    public function run(): void
    {
        $header = Menu::firstOrCreate(['location' => 'header'], ['name' => 'Header menu']);
        $footer = Menu::firstOrCreate(['location' => 'footer'], ['name' => 'Footer menu']);

        $headerItems = [
            ['label' => 'Sports', 'url' => '/sports'],
            ['label' => 'Predictions', 'url' => '/predictions'],
            ['label' => 'Blog', 'url' => '/articles'],
        ];

        foreach ($headerItems as $index => $item) {
            $header->items()->firstOrCreate(['label' => $item['label']], [...$item, 'display_order' => $index]);
        }

        $footerItems = [
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
            ['label' => 'Terms', 'url' => '/terms'],
            ['label' => 'FAQ', 'url' => '/faq'],
        ];

        foreach ($footerItems as $index => $item) {
            $footer->items()->firstOrCreate(['label' => $item['label']], [...$item, 'display_order' => $index]);
        }
    }
}
