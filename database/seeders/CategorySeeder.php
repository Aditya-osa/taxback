<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tax Law',
                'slug' => 'tax-law',
                'description' => 'Articles related to tax regulations, compliance, and planning',
                'color' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Corporate Law',
                'slug' => 'corporate-law',
                'description' => 'Business law, company formation, and corporate governance',
                'color' => '#10B981',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Legal Updates',
                'slug' => 'legal-updates',
                'description' => 'Latest news and updates in the legal field',
                'color' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Case Studies',
                'slug' => 'case-studies',
                'description' => 'Analysis of important legal cases and precedents',
                'color' => '#EF4444',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Business Tips',
                'slug' => 'business-tips',
                'description' => 'Practical advice for business owners and entrepreneurs',
                'color' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
