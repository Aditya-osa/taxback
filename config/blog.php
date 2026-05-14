<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blog Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the blog functionality.
    | You can customize these settings according to your needs.
    |
    */

    'admin_email' => env('BLOG_ADMIN_EMAIL', 'admin@taxlegal.com'),

    'posts' => [
        'per_page' => env('BLOG_POSTS_PER_PAGE', 12),
        'featured_count' => env('BLOG_FEATURED_COUNT', 5),
        'popular_count' => env('BLOG_POPULAR_COUNT', 10),
        'excerpt_length' => env('BLOG_EXCERPT_LENGTH', 150),
    ],

    'comments' => [
        'per_page' => env('BLOG_COMMENTS_PER_PAGE', 20),
        'auto_approve_users' => env('BLOG_AUTO_APPROVE_USERS', false),
        'require_email' => env('BLOG_REQUIRE_EMAIL', true),
        'max_length' => env('BLOG_COMMENT_MAX_LENGTH', 2000),
    ],

    'seo' => [
        'title_separator' => ' - ',
        'meta_description_length' => 160,
        'default_title' => 'TaxLegal Blog',
        'default_description' => 'Expert insights on tax law, corporate law, and business compliance in India',
    ],

    'upload' => [
        'max_file_size' => env('BLOG_MAX_FILE_SIZE', 2048), // KB
        'allowed_types' => ['jpeg', 'jpg', 'png', 'gif'],
        'image_quality' => env('BLOG_IMAGE_QUALITY', 85),
    ],

    'cache' => [
        'enabled' => env('BLOG_CACHE_ENABLED', true),
        'ttl' => env('BLOG_CACHE_TTL', 3600), // seconds
    ],
];
