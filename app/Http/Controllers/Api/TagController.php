<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Tag::active()->withCount('publishedPosts');

        if ($request->boolean('popular')) {
            $query->popular();
        } else {
            $query->orderBy('name');
        }

        $tags = $query->get();

        return response()->json([
            'data' => TagResource::collection($tags),
        ]);
    }

    public function show(Tag $tag): JsonResponse
    {
        $tag->load(['publishedPosts' => function ($query) {
            $query->with(['author', 'category', 'tags'])->orderBy('published_at', 'desc');
        }]);

        return response()->json([
            'data' => new TagResource($tag),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7', // hex color
            'is_active' => 'boolean',
        ]);

        $tag = Tag::create($validated);

        return response()->json([
            'data' => new TagResource($tag),
            'message' => 'Tag created successfully',
        ], 201);
    }

    public function update(Request $request, Tag $tag): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $tag->update($validated);

        return response()->json([
            'data' => new TagResource($tag),
            'message' => 'Tag updated successfully',
        ]);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully',
        ]);
    }
}
