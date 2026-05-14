<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Tax Planning',
                'slug' => 'tax-planning',
                'description' => 'Strategies for optimizing tax obligations',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'GST',
                'slug' => 'gst',
                'description' => 'Goods and Services Tax related content',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Income Tax',
                'slug' => 'income-tax',
                'description' => 'Personal and corporate income tax matters',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Company Registration',
                'slug' => 'company-registration',
                'description' => 'Business incorporation and registration processes',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Compliance',
                'slug' => 'compliance',
                'description' => 'Regulatory compliance requirements',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Legal News',
                'slug' => 'legal-news',
                'description' => 'Latest developments in law and regulations',
                'color' => '#EC4899',
                'is_active' => true,
            ],
            [
                'name' => 'Business Law',
                'slug' => 'business-law',
                'description' => 'Commercial and business legal matters',
                'color' => '#14B8A6',
                'is_active' => true,
            ],
            [
                'name' => 'Audit',
                'slug' => 'audit',
                'description' => 'Financial and tax audit procedures',
                'color' => '#F97316',
                'is_active' => true,
            ],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
