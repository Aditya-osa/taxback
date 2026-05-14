<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Post::with(['author', 'category', 'tags'])
                    ->published();

        // Filter by category
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->byTag($request->tag);
        }

        // Search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Featured posts
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Popular posts
        if ($request->boolean('popular')) {
            $query->popular();
        }

        // Pagination
        $posts = $query->orderBy('published_at', 'desc')
                      ->paginate($request->get('per_page', 12));

        return response()->json([
            'data' => PostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    public function show(Post $post): JsonResponse
    {
        // Increment views
        $post->incrementViews();

        return response()->json([
            'data' => new PostResource($post->load(['author', 'category', 'tags', 'approvedComments'])),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published_at' => 'nullable|date',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $validated['featured_image'] = $path;
        }

        $validated['author_id'] = auth()->id();

        $post = Post::create($validated);

        // Sync tags
        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return response()->json([
            'data' => new PostResource($post->load(['author', 'category', 'tags'])),
            'message' => 'Post created successfully',
        ], 201);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published_at' => 'nullable|date',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $path = $request->file('featured_image')->store('posts', 'public');
            $validated['featured_image'] = $path;
        }

        $post->update($validated);

        // Sync tags
        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return response()->json([
            'data' => new PostResource($post->load(['author', 'category', 'tags'])),
            'message' => 'Post updated successfully',
        ]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }

    public function featured(): JsonResponse
    {
        $posts = Post::with(['author', 'category', 'tags'])
                    ->published()
                    ->featured()
                    ->orderBy('published_at', 'desc')
                    ->limit(5)
                    ->get();

        return response()->json([
            'data' => PostResource::collection($posts),
        ]);
    }

    public function popular(): JsonResponse
    {
        $posts = Post::with(['author', 'category', 'tags'])
                    ->published()
                    ->popular()
                    ->orderBy('published_at', 'desc')
                    ->limit(10)
                    ->get();

        return response()->json([
            'data' => PostResource::collection($posts),
        ]);
    }
}
