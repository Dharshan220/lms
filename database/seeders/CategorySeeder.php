<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hardware & Electronics', 'slug' => 'hardware-electronics', 'icon' => 'bi-cpu', 'color' => '#FF6B35'],
            ['name' => 'Software & Programming', 'slug' => 'software-programming', 'icon' => 'bi-code-slash', 'color' => '#3498DB'],
            ['name' => 'IoT & Networking', 'slug' => 'iot-networking', 'icon' => 'bi-router', 'color' => '#4ECDC4'],
            ['name' => 'AI & Machine Learning', 'slug' => 'ai-machine-learning', 'icon' => 'bi-brain', 'color' => '#9B59B6'],
            ['name' => 'Robotics', 'slug' => 'robotics', 'icon' => 'bi-robot', 'color' => '#E74C3C'],
            ['name' => 'Embedded Systems', 'slug' => 'embedded-systems', 'icon' => 'bi-motherboard', 'color' => '#2ECC71'],
            ['name' => 'STEM & Projects', 'slug' => 'stem-projects', 'icon' => 'bi-tools', 'color' => '#F39C12'],
            ['name' => 'PCB & Hardware Design', 'slug' => 'pcb-hardware-design', 'icon' => 'bi-pencil', 'color' => '#6610F2'],
            ['name' => 'Cloud & Technology', 'slug' => 'cloud-technology', 'icon' => 'bi-cloud', 'color' => '#0D6EFD'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, [
                    'description' => 'Learn about ' . $cat['name'],
                    'is_active' => true,
                ])
            );
        }

        $this->command->info('Categories seeded successfully.');
    }
}
