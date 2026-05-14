<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'content' => $this->content,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'author' => [
                'id' => $this->user?->id,
                'name' => $this->author_name,
                'email' => $this->author_email,
                'website' => $this->author_website,
                'is_guest' => $this->user === null,
            ],
            
            'post' => $this->when($this->relationLoaded('post'), [
                'id' => $this->post->id,
                'title' => $this->post->title,
                'slug' => $this->post->slug,
            ]),
            
            'replies' => $this->when($this->relationLoaded('replies'), function () {
                return CommentResource::collection($this->replies);
            }),
            
            'replies_count' => $this->when($this->relationLoaded('replies'), $this->replies->count()),
            
            'can_edit' => $request->user()?->can('update', $this),
            'can_delete' => $request->user()?->can('delete', $this),
            'can_moderate' => $request->user()?->can('moderate', $this),
        ];
    }
}
