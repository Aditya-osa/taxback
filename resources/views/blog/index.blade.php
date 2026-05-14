@extends('layouts.app')

@section('title', 'Blog')
@section('description', 'Latest articles on tax and legal matters')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">TaxLegal Blog</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Expert insights and analysis on tax regulations, legal compliance, and business law
        </p>
    </div>

    <!-- Featured Posts -->
    @if($featuredPosts->count() > 0)
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredPosts as $post)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($post->featured_image)
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center mb-2">
                                @if($post->category)
                                    <span class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full" 
                                          style="background-color: {{ $post->category->color ?? '#3B82F6' }}">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <span class="ml-auto text-sm text-gray-500">
                                    {{ $post->published_at->format('M j, Y') }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $post->author->avatar }}" alt="{{ $post->author->name }}" 
                                         class="w-6 h-6 rounded-full mr-2">
                                    <span class="text-sm text-gray-700">{{ $post->author->name }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>{{ $post->reading_time }} min read</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->views }} views</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Search and Filters -->
    <section class="mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('blog.search') }}" class="mb-4">
                <div class="flex gap-4">
                    <input type="text" name="q" placeholder="Search articles..." 
                           value="{{ request('q') }}"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Search
                    </button>
                </div>
            </form>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}" 
                   class="px-4 py-2 rounded-full text-sm {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    All Categories
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog.index', ['category' => $category->id]) }}" 
                       class="px-4 py-2 rounded-full text-sm {{ request('category') == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ $category->name }} ({{ $category->post_count }})
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Recent Posts -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Recent Articles</h2>
        
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($post->featured_image)
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center mb-2">
                                @if($post->category)
                                    <span class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full" 
                                          style="background-color: {{ $post->category->color ?? '#3B82F6' }}">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <span class="ml-auto text-sm text-gray-500">
                                    {{ $post->published_at->format('M j, Y') }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $post->author->avatar }}" alt="{{ $post->author->name }}" 
                                         class="w-6 h-6 rounded-full mr-2">
                                    <span class="text-sm text-gray-700">{{ $post->author->name }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>{{ $post->reading_time }} min read</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->views }} views</span>
                                </div>
                            </div>
                            
                            @if($post->tags->count() > 0)
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($post->tags->take(3) as $tag)
                                        <a href="{{ route('blog.tag', $tag->slug) }}" 
                                           class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">No articles found.</p>
            </div>
        @endif
    </section>

    <!-- Popular Tags -->
    @if($tags->count() > 0)
        <section class="mt-12">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Popular Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}" 
                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">
                        #{{ $tag->name }} ({{ $tag->post_count }})
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
