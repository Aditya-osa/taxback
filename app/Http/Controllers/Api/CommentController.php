<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Comment::with(['post', 'user', 'replies']);

        // Filter by post
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Only top-level comments by default
        if (!$request->boolean('include_replies')) {
            $query->whereNull('parent_id');
        }

        $comments = $query->orderBy('created_at', 'desc')
                         ->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => CommentResource::collection($comments),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }

    public function show(Comment $comment): JsonResponse
    {
        $comment->load(['post', 'user', 'replies' => function ($query) {
            $query->with('user')->orderBy('created_at', 'asc');
        }]);

        return response()->json([
            'data' => new CommentResource($comment),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:2000',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
            'guest_name' => 'required_without:user_id|string|max:255',
            'guest_email' => 'required_without:user_id|email|max:255',
            'guest_website' => 'nullable|url|max:255',
        ]);

        // Set user ID if authenticated
        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            unset($validated['guest_name'], $validated['guest_email'], $validated['guest_website']);
        }

        // Set IP address
        $validated['ip_address'] = $request->ip();

        // Set default status to pending for guest comments
        if (!auth()->check()) {
            $validated['status'] = 'pending';
        } else {
            $validated['status'] = 'approved';
        }

        $comment = Comment::create($validated);

        return response()->json([
            'data' => new CommentResource($comment->load(['user', 'post'])),
            'message' => 'Comment submitted successfully',
        ], 201);
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:2000',
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        $comment->update($validated);

        return response()->json([
            'data' => new CommentResource($comment->load(['user', 'post'])),
            'message' => 'Comment updated successfully',
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }

    public function approve(Comment $comment): JsonResponse
    {
        $this->authorize('moderate', $comment);

        $comment->update(['status' => 'approved']);

        return response()->json([
            'data' => new CommentResource($comment),
            'message' => 'Comment approved successfully',
        ]);
    }

    public function reject(Comment $comment): JsonResponse
    {
        $this->authorize('moderate', $comment);

        $comment->update(['status' => 'rejected']);

        return response()->json([
            'data' => new CommentResource($comment),
            'message' => 'Comment rejected successfully',
        ]);
    }

    public function pending(): JsonResponse
    {
        $comments = Comment::with(['post', 'user'])
                          ->pending()
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return response()->json([
            'data' => CommentResource::collection($comments),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }
}
