@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->excerpt ?: Str::limit(strip_tags($post->content), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('blog.index') }}" class="hover:text-gray-900">Blog</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900">{{ $post->title }}</li>
        </ol>
    </nav>

    <!-- Article Header -->
    <article class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($post->featured_image)
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-64 md:h-96 object-cover">
        @endif
        
        <div class="p-8">
            <!-- Category and Date -->
            <div class="flex items-center mb-4">
                @if($post->category)
                    <a href="{{ route('blog.category', $post->category->slug) }}" 
                       class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full hover:opacity-90"
                       style="background-color: {{ $post->category->color ?? '#3B82F6' }}">
                        {{ $post->category->name }}
                    </a>
                @endif
                <span class="ml-auto text-gray-500">
                    {{ $post->published_at->format('F j, Y') }}
                </span>
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                {{ $post->title }}
            </h1>
            
            <!-- Author Info -->
            <div class="flex items-center mb-8 pb-8 border-b">
                <img src="{{ $post->author->avatar }}" alt="{{ $post->author->name }}" 
                     class="w-12 h-12 rounded-full mr-4">
                <div class="flex-1">
                    <div class="font-semibold text-gray-900">{{ $post->author->name }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $post->reading_time }} min read • {{ $post->views }} views
                    </div>
                </div>
                
                <!-- Share Buttons -->
                <div class="flex items-center space-x-2">
                    <button onclick="window.open('https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}', '_blank')" 
                            class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </button>
                    <button onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}', '_blank')" 
                            class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Article Content -->
            <div class="prose max-w-none">
                {!! $post->content !!}
            </div>
            
            <!-- Tags -->
            @if($post->tags->count() > 0)
                <div class="mt-8 pt-8 border-t">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}" 
                               class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedPosts as $related)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($related->featured_image)
                            <img src="{{ $related->featured_image }}" alt="{{ $related->title }}" class="w-full h-32 object-cover">
                        @else
                            <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex items-center mb-2">
                                @if($related->category)
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-white rounded-full" 
                                          style="background-color: {{ $related->category->color ?? '#3B82F6' }}">
                                        {{ $related->category->name }}
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                <a href="{{ route('blog.show', $related->slug) }}" class="hover:text-blue-600">
                                    {{ $related->title }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <span>{{ $related->published_at->format('M j, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $related->reading_time }} min read</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Comments Section -->
    <section class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            Comments ({{ $post->comments->where('status', 'approved')->count() }})
        </h2>
        
        <!-- Comment Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Leave a Comment</h3>
            <form action="{{ route('blog.comments.store', $post->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="content" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Share your thoughts..."></textarea>
                </div>
                
                @guest
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <input type="text" name="guest_name" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Your Name">
                        </div>
                        <div>
                            <input type="email" name="guest_email" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Your Email">
                        </div>
                    </div>
                    <div class="mb-4">
                        <input type="url" name="guest_website"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Your Website (optional)">
                    </div>
                @endguest
                
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Post Comment
                </button>
                <p class="text-sm text-gray-600 mt-2">
                    Your comment will be published after moderation.
                </p>
            </form>
        </div>
        
        <!-- Comments List -->
        <div class="space-y-6">
            @foreach($post->approvedComments as $comment)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-start">
                        <img src="{{ $comment->user ? $comment->user->avatar : 'https://www.gravatar.com/avatar/' . md5($comment->guest_email) . '?s=200&d=identicon' }}" 
                             alt="{{ $comment->author_name }}" class="w-10 h-10 rounded-full mr-4">
                        
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $comment->author_name }}</h4>
                                @if($comment->author_website)
                                    <a href="{{ $comment->author_website }}" target="_blank" rel="noopener" 
                                       class="ml-2 text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                            <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                        </svg>
                                    </a>
                                @endif
                                <span class="ml-auto text-sm text-gray-500">
                                    {{ $comment->created_at->format('M j, Y \a\t g:i A') }}
                                </span>
                            </div>
                            
                            <div class="prose max-w-none">
                                <p>{{ $comment->content }}</p>
                            </div>
                            
                            <!-- Reply Button -->
                            <button onclick="toggleReplyForm({{ $comment->id }})" 
                                    class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                                Reply
                            </button>
                            
                            <!-- Reply Form (Hidden by default) -->
                            <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                                <form action="{{ route('blog.comments.store', $post->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div class="mb-3">
                                        <textarea name="content" rows="3" required
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                  placeholder="Write your reply..."></textarea>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-4 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                            Reply
                                        </button>
                                        <button type="button" onclick="toggleReplyForm({{ $comment->id }})" 
                                                class="px-4 py-1 bg-gray-300 text-gray-700 text-sm rounded hover:bg-gray-400">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                                <div class="mt-4 space-y-4">
                                    @foreach($comment->replies as $reply)
                                        <div class="flex items-start bg-gray-50 rounded-lg p-4">
                                            <img src="{{ $reply->user ? $reply->user->avatar : 'https://www.gravatar.com/avatar/' . md5($reply->guest_email) . '?s=200&d=identicon' }}" 
                                                 alt="{{ $reply->author_name }}" class="w-8 h-8 rounded-full mr-3">
                                            
                                            <div class="flex-1">
                                                <div class="flex items-center mb-1">
                                                    <h5 class="font-semibold text-gray-900 text-sm">{{ $reply->author_name }}</h5>
                                                    <span class="ml-auto text-xs text-gray-500">
                                                        {{ $reply->created_at->format('M j, Y \a\t g:i A') }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.classList.toggle('hidden');
}
</script>
@endsection
