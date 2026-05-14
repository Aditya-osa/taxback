<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when($request->routeIs('posts.show'), $this->content),
            'featured_image' => $this->featured_image ? url('storage/' . $this->featured_image) : null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'views' => $this->views,
            'reading_time' => $this->reading_time,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'email' => $this->author->email,
            ] : null,
            
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'color' => $this->category->color,
            ] : null,
            
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->color,
                ];
            }),
            
            'comments_count' => $this->when($request->routeIs('posts.show'), $this->approvedComments()->count()),
            
            'meta' => [
                'word_count' => str_word_count(strip_tags($this->content)),
                'estimated_reading_time' => $this->reading_time,
            ],
        ];
    }
}
