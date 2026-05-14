<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Post::all();

        $comments = [
            [
                'content' => 'This is a very comprehensive guide! The step-by-step process really helped me understand the requirements better.',
                'status' => 'approved',
                'guest_name' => 'Rahul Sharma',
                'guest_email' => 'rahul.sharma@email.com',
                'guest_website' => 'https://rahulbusiness.com',
            ],
            [
                'content' => 'Thank you for this detailed explanation. I was confused about the documentation process, but this article cleared everything up.',
                'status' => 'approved',
                'guest_name' => 'Priya Patel',
                'guest_email' => 'priya.p@email.com',
            ],
            [
                'content' => 'Great article! Do you have any specific advice for startups in the tech sector?',
                'status' => 'approved',
                'guest_name' => 'Amit Kumar',
                'guest_email' => 'amit.kumar@email.com',
            ],
            [
                'content' => 'This information is very helpful. We are currently going through this process and your guide has been invaluable.',
                'status' => 'approved',
                'guest_name' => 'Sneha Reddy',
                'guest_email' => 'sneha.reddy@email.com',
            ],
            [
                'content' => 'Is there any way to expedite the process? We are facing some time constraints with our business launch.',
                'status' => 'pending',
                'guest_name' => 'Vikram Singh',
                'guest_email' => 'vikram.singh@email.com',
            ],
            [
                'content' => 'Excellent breakdown of the requirements. The checklist at the end is particularly useful.',
                'status' => 'approved',
                'guest_name' => 'Anjali Nair',
                'guest_email' => 'anjali.nair@email.com',
            ],
            [
                'content' => 'Could you write more about the common challenges and how to overcome them?',
                'status' => 'approved',
                'guest_name' => 'Rohit Gupta',
                'guest_email' => 'rohit.gupta@email.com',
            ],
            [
                'content' => 'This is exactly what I was looking for! Saved me hours of research.',
                'status' => 'approved',
                'guest_name' => 'Kavita Mehta',
                'guest_email' => 'kavita.mehta@email.com',
            ],
        ];

        foreach ($posts as $post) {
            // Add 3-5 comments per post
            $postComments = collect($comments)->random(rand(3, 5));
            
            foreach ($postComments as $commentData) {
                $comment = Comment::create([
                    'content' => $commentData['content'],
                    'status' => $commentData['status'],
                    'post_id' => $post->id,
                    'guest_name' => $commentData['guest_name'],
                    'guest_email' => $commentData['guest_email'],
                    'guest_website' => $commentData['guest_website'] ?? null,
                    'ip_address' => '192.168.1.' . rand(1, 254),
                    'created_at' => $post->published_at->addDays(rand(1, 30)),
                ]);

                // Add some replies to approved comments
                if ($comment->status === 'approved' && rand(0, 1)) {
                    Comment::create([
                        'content' => 'Thank you for your comment! We\'re glad you found the article helpful. Feel free to reach out if you have any specific questions.',
                        'status' => 'approved',
                        'post_id' => $post->id,
                        'parent_id' => $comment->id,
                        'guest_name' => 'TaxLegal Team',
                        'guest_email' => 'team@taxlegal.com',
                        'created_at' => $comment->created_at->addHours(rand(1, 24)),
                    ]);
                }
            }
        }
    }
}
