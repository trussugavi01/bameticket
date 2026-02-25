<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Awards Ceremony',
                'slug' => 'awards-ceremony',
                'icon' => '🏆',
                'color' => '#f59e0b',
                'description' => 'Recognition and awards events celebrating excellence',
                'sort_order' => 1,
            ],
            [
                'name' => 'Conference',
                'slug' => 'conference',
                'icon' => '🎤',
                'color' => '#3b82f6',
                'description' => 'Professional conferences and speaking events',
                'sort_order' => 2,
            ],
            [
                'name' => 'Gala Dinner',
                'slug' => 'gala-dinner',
                'icon' => '✨',
                'color' => '#8b5cf6',
                'description' => 'Formal dining and celebration events',
                'sort_order' => 3,
            ],
            [
                'name' => 'Workshop',
                'slug' => 'workshop',
                'icon' => '🛠️',
                'color' => '#10b981',
                'description' => 'Hands-on learning and training sessions',
                'sort_order' => 4,
            ],
            [
                'name' => 'Networking',
                'slug' => 'networking',
                'icon' => '🤝',
                'color' => '#0d9488',
                'description' => 'Professional networking and social events',
                'sort_order' => 5,
            ],
            [
                'name' => 'Seminar',
                'slug' => 'seminar',
                'icon' => '📚',
                'color' => '#6366f1',
                'description' => 'Educational seminars and presentations',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            EventCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
