@extends('layouts.app')

@section('title', 'Search Results')
@section('description', 'Search results for ' . request('q'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Search Results</h1>
        <p class="text-gray-600">
            @if(request('q'))
                Showing results for: <strong>"{{ request('q') }}"</strong>
            @else
                Please enter a search term.
            @endif
        </p>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form method="GET" action="{{ route('blog.search') }}">
            <div class="flex gap-4">
                <input type="text" name="q" placeholder="Search articles..." 
                       value="{{ request('q') }}"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    @if(request('q') && $posts->count() > 0)
        <div class="mb-4">
            <p class="text-gray-600">Found {{ $posts->total() }} result{{ $posts->total() != 1 ? 's' : '' }}</p>
        </div>
        
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
    @elseif(request('q'))
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg">No results found for "{{ request('q') }}".</p>
            <p class="text-gray-600 mt-2">Try different keywords or browse our categories.</p>
            <a href="{{ route('blog.index') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Browse All Articles
            </a>
        </div>
    @endif
</div>
@endsection
