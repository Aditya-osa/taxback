<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'color' => $this->color,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'posts_count' => $this->when(isset($this->posts_count), $this->posts_count),
            'post_count' => $this->when(!isset($this->posts_count), $this->post_count),
            
            'posts' => $this->when($request->routeIs('categories.show'), function () {
                return $this->publishedPosts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'slug' => $post->slug,
                        'excerpt' => $post->excerpt,
                        'featured_image' => $post->featured_image ? url('storage/' . $post->featured_image) : null,
                        'published_at' => $post->published_at,
                        'views' => $post->views,
                        'reading_time' => $post->reading_time,
                    ];
                });
            }),
        ];
    }
}
