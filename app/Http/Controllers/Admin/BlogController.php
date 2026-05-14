<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $posts = Post::with(['author', 'category', 'tags'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('content', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Category::active()->ordered()->get();
        $statuses = ['draft', 'published', 'archived'];

        return view('admin.blog.index', compact('posts', 'categories', 'statuses'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $tags = Tag::active()->get();

        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('tags');
        $data['author_id'] = auth()->id();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($data['status'] === 'published' && !$data['published_at']) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);

        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return redirect()->route('admin.blog.edit', $post)
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $post->load('tags');
        $categories = Category::active()->ordered()->get();
        $tags = Tag::active()->get();

        return view('admin.blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('tags');
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($data['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        } elseif ($data['status'] !== 'published') {
            $data['published_at'] = null;
        }

        $post->update($data);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.blog.edit', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function duplicate(Post $post)
    {
        $newPost = $post->replicate();
        $newPost->title = $post->title . ' (Copy)';
        $newPost->slug = Str::slug($newPost->title);
        $newPost->status = 'draft';
        $newPost->published_at = null;
        $newPost->views = 0;
        $newPost->author_id = auth()->id();
        $newPost->save();

        $newPost->tags()->attach($post->tags->pluck('id'));

        return redirect()->route('admin.blog.edit', $newPost)
            ->with('success', 'Post duplicated successfully.');
    }

    public function comments()
    {
        $comments = Comment::with(['post', 'user', 'parent'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.blog.comments', compact('comments'));
    }

    public function approveComment(Comment $comment)
    {
        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Comment approved.');
    }

    public function rejectComment(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'Comment rejected.');
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    public function categories()
    {
        $categories = Category::withCount('posts')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.blog.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Category::create($data);

        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return back()->with('success', 'Category updated successfully.');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }

    public function tags()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.blog.tags', compact('tags'));
    }

    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Tag::create($data);

        return back()->with('success', 'Tag created successfully.');
    }

    public function updateTag(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag->update($data);

        return back()->with('success', 'Tag updated successfully.');
    }

    public function deleteTag(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'Tag deleted successfully.');
    }
}
