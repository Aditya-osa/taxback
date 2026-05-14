<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::published()
            ->with(['author', 'category', 'tags'])
            ->when($request->category, function ($query, $category) {
                return $query->byCategory($category);
            })
            ->when($request->tag, function ($query, $tag) {
                return $query->byTag($tag);
            })
            ->when($request->search, function ($query, $search) {
                return $query->search($search);
            })
            ->when($request->featured, function ($query) {
                return $query->featured();
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $featuredPosts = Post::published()
            ->featured()
            ->with(['author', 'category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->withCount('publishedPosts')
            ->get();

        $tags = Tag::active()
            ->popular()
            ->take(20)
            ->get();

        return view('blog.index', compact('posts', 'featuredPosts', 'categories', 'tags'));
    }

    public function show($slug)
    {
        $post = Post::published()
            ->with(['author', 'category', 'tags', 'comments' => function ($query) {
                $query->approved()->with(['replies' => function ($q) {
                    $q->approved()->with('replies');
                }, 'user'])->orderBy('created_at', 'asc');
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        $post->incrementViews();

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->orWhereHas('tags', function ($query) use ($post) {
                $query->whereIn('tags.id', $post->tags->pluck('id'));
            })
            ->with(['author', 'category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $posts = Post::published()
            ->byCategory($category->id)
            ->with(['author', 'category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog.category', compact('category', 'posts'));
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $posts = Post::published()
            ->byTag($tag->slug)
            ->with(['author', 'category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog.tag', compact('tag', 'posts'));
    }

    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|max:255']);

        $posts = Post::published()
            ->search($request->q)
            ->with(['author', 'category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog.search', compact('posts'));
    }

    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
            'guest_name' => 'required_if:user_id,null|string|max:255',
            'guest_email' => 'required_if:user_id,null|email|max:255',
            'guest_website' => 'nullable|url|max:255',
        ]);

        $post = Post::published()->findOrFail($postId);

        $comment = $post->comments()->create([
            'content' => $request->content,
            'parent_id' => $request->parent_id,
            'user_id' => auth()->id(),
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_website' => $request->guest_website,
            'ip_address' => $request->ip(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }

    public function feed()
    {
        $posts = Post::published()
            ->with(['author', 'category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->take(20)
            ->get();

        return response()->view('blog.feed', compact('posts'), 200)
            ->header('Content-Type', 'application/rss+xml');
    }

    public function sitemap()
    {
        $posts = Post::published()
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        $categories = Category::active()
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        $tags = Tag::active()
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        return response()->view('blog.sitemap', compact('posts', 'categories', 'tags'), 200)
            ->header('Content-Type', 'application/xml');
    }
}
