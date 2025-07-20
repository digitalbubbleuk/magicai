<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CreativeSuiteCategory;
use Illuminate\Support\Str;

class CreativeSuiteCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'key' => 'beauty',
                'name' => 'Beauty',
                'description' => 'Beauty and cosmetics templates',
                'icon' => 'fas fa-spa',
                'color' => '#FF69B4',
                'sort_order' => 10
            ],
            [
                'key' => 'business',
                'name' => 'Business',
                'description' => 'Professional business templates',
                'icon' => 'fas fa-briefcase',
                'color' => '#2563EB',
                'sort_order' => 20
            ],
            [
                'key' => 'events',
                'name' => 'Events',
                'description' => 'Event and celebration templates',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#7C3AED',
                'sort_order' => 30
            ],
            [
                'key' => 'fashion',
                'name' => 'Fashion',
                'description' => 'Fashion and style templates',
                'icon' => 'fas fa-tshirt',
                'color' => '#EC4899',
                'sort_order' => 40
            ],
            [
                'key' => 'fitness',
                'name' => 'Fitness',
                'description' => 'Health and fitness templates',
                'icon' => 'fas fa-dumbbell',
                'color' => '#059669',
                'sort_order' => 50
            ],
            [
                'key' => 'food',
                'name' => 'Food',
                'description' => 'Food and restaurant templates',
                'icon' => 'fas fa-utensils',
                'color' => '#DC2626',
                'sort_order' => 60
            ],
            [
                'key' => 'marketing',
                'name' => 'Marketing',
                'description' => 'Marketing and promotional templates',
                'icon' => 'fas fa-bullhorn',
                'color' => '#F59E0B',
                'sort_order' => 70
            ],
            [
                'key' => 'shopping',
                'name' => 'Shopping',
                'description' => 'E-commerce and shopping templates',
                'icon' => 'fas fa-shopping-bag',
                'color' => '#8B5CF6',
                'sort_order' => 80
            ],
            [
                'key' => 'social_media',
                'name' => 'Social Media',
                'description' => 'Social media content templates',
                'icon' => 'fas fa-share-alt',
                'color' => '#3B82F6',
                'sort_order' => 90
            ],
            [
                'key' => 'technology',
                'name' => 'Technology',
                'description' => 'Technology and digital templates',
                'icon' => 'fas fa-laptop',
                'color' => '#6366F1',
                'sort_order' => 100
            ]
        ];

        foreach ($categories as $categoryData) {
            $categoryData['slug'] = Str::slug($categoryData['name']);
            
            CreativeSuiteCategory::updateOrCreate(
                ['key' => $categoryData['key']],
                $categoryData
            );
        }

        $this->command->info('Created ' . count($categories) . ' categories.');
    }
}
