@extends('layouts.app')

@section('title', $category->name . ' Articles')
@section('description', 'Articles in ' . $category->name . ' category' . ($category->description ? ': ' . $category->description : ''))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Category Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $category->description }}</p>
        @endif
    </div>

    <!-- Posts Grid -->
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
                            </div>
                        </div>
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
            <p class="text-gray-600 text-lg">No articles found in this category.</p>
            <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                Browse all articles
            </a>
        </div>
    @endif
</div>
@endsection
