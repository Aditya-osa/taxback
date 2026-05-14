<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = User::all();
        $categories = Category::all();
        $tags = Tag::all();

        $posts = [
            [
                'title' => 'Understanding GST Registration: A Complete Guide',
                'slug' => 'understanding-gst-registration-complete-guide',
                'excerpt' => 'Learn everything about GST registration process, requirements, and benefits for your business in India.',
                'content' => $this->generatePostContent('GST Registration'),
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(7),
                'category_slug' => 'tax-law',
                'tag_slugs' => ['gst', 'tax-planning', 'compliance'],
            ],
            [
                'title' => 'Company Registration in India: Step by Step Process',
                'slug' => 'company-registration-india-step-by-step-process',
                'excerpt' => 'Complete guide to register your company in India, including types of companies and required documents.',
                'content' => $this->generatePostContent('Company Registration'),
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(14),
                'category_slug' => 'corporate-law',
                'tag_slugs' => ['company-registration', 'business-law', 'compliance'],
            ],
            [
                'title' => 'Income Tax Filing: Common Mistakes to Avoid',
                'slug' => 'income-tax-filing-common-mistakes-to-avoid',
                'excerpt' => 'Discover the most common mistakes taxpayers make when filing income tax returns and how to avoid them.',
                'content' => $this->generatePostContent('Income Tax Filing'),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(21),
                'category_slug' => 'tax-law',
                'tag_slugs' => ['income-tax', 'tax-planning', 'audit'],
            ],
            [
                'title' => 'New Tax Regulations 2024: What Business Owners Need to Know',
                'slug' => 'new-tax-regulations-2024-what-business-owners-need-to-know',
                'excerpt' => 'Stay updated with the latest tax regulations affecting businesses in 2024 and how to prepare for them.',
                'content' => $this->generatePostContent('New Tax Regulations'),
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(3),
                'category_slug' => 'legal-updates',
                'tag_slugs' => ['legal-news', 'compliance', 'tax-planning'],
            ],
            [
                'title' => 'Case Study: Successful Tax Planning for a Manufacturing Company',
                'slug' => 'case-study-successful-tax-planning-manufacturing-company',
                'excerpt' => 'Learn how we helped a manufacturing company optimize their tax structure and save significantly on taxes.',
                'content' => $this->generatePostContent('Tax Planning Case Study'),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(30),
                'category_slug' => 'case-studies',
                'tag_slugs' => ['tax-planning', 'audit', 'business-law'],
            ],
            [
                'title' => 'Business Compliance Checklist for Startups',
                'slug' => 'business-compliance-checklist-startups',
                'excerpt' => 'Essential compliance requirements every startup must follow to avoid legal issues in India.',
                'content' => $this->generatePostContent('Business Compliance'),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(10),
                'category_slug' => 'business-tips',
                'tag_slugs' => ['compliance', 'company-registration', 'business-law'],
            ],
        ];

        foreach ($posts as $postData) {
            $category = $categories->where('slug', $postData['category_slug'])->first();
            $postTags = $tags->whereIn('slug', $postData['tag_slugs']);
            
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => $postData['slug'],
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'status' => $postData['status'],
                'is_featured' => $postData['is_featured'],
                'published_at' => $postData['published_at'],
                'author_id' => $authors->random()->id,
                'category_id' => $category->id,
                'views' => rand(100, 5000),
            ]);

            $post->tags()->attach($postTags->pluck('id'));
        }
    }

    private function generatePostContent($topic): string
    {
        return <<<HTML
        <h2>Introduction</h2>
        <p>In today's complex business environment, understanding {$topic} is crucial for success. This comprehensive guide will walk you through everything you need to know about this important topic.</p>
        
        <h2>Key Requirements</h2>
        <p>Before diving into the process, it's important to understand the key requirements and prerequisites. These include proper documentation, understanding of legal frameworks, and compliance with regulatory standards.</p>
        
        <h3>Documentation Needed</h3>
        <ul>
            <li>Identity proof of all stakeholders</li>
            <li>Address verification documents</li>
            <li>Business registration certificates</li>
            <li>Financial statements and records</li>
        </ul>
        
        <h2>Step-by-Step Process</h2>
        <p>The process can be broken down into several key steps that need to be followed systematically:</p>
        
        <h3>Step 1: Initial Assessment</h3>
        <p>Begin by conducting a thorough assessment of your business needs and requirements. This will help determine the specific approach you need to take.</p>
        
        <h3>Step 2: Documentation Preparation</h3>
        <p>Gather all necessary documents and ensure they are up to date. This is a critical step that can significantly impact the processing time.</p>
        
        <h3>Step 3: Application Submission</h3>
        <p>Submit your application through the appropriate channels, ensuring all forms are correctly filled and supporting documents are attached.</p>
        
        <h3>Step 4: Review and Approval</h3>
        <p>Wait for the reviewing authority to process your application. This may take anywhere from a few days to several weeks depending on the complexity.</p>
        
        <h2>Common Challenges</h2>
        <p>Many businesses face common challenges during this process. Being aware of these can help you prepare better:</p>
        
        <ul>
            <li>Incomplete documentation leading to delays</li>
            <li>Misunderstanding of regulatory requirements</li>
            <li>Changes in laws or regulations during the process</li>
            <li>Technical issues with online portals</li>
        </ul>
        
        <h2>Best Practices</h2>
        <p>To ensure a smooth process, follow these best practices:</p>
        
        <ol>
            <li>Start early and plan ahead</li>
            <li>Keep all documents organized and accessible</li>
            <li>Stay updated with latest regulations</li>
            <li>Seek professional advice when needed</li>
            <li>Maintain proper records throughout</li>
        </ol>
        
        <h2>Conclusion</h2>
        <p>Navigating {$topic} requires careful planning and attention to detail. By following this guide and seeking professional assistance when needed, you can ensure compliance and avoid potential pitfalls.</p>
        
        <p><strong>Need assistance with {$topic}?</strong> Contact our expert team today for personalized guidance and support.</p>
        HTML;
    }
}
