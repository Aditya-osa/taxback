<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaxLegal Blog') | TaxLegal Blog</title>
    <meta name="description" content="@yield('description', 'Expert insights on tax and legal matters')">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        .prose {
            max-width: none;
        }
        .prose h1, .prose h2, .prose h3 {
            @apply font-bold text-gray-900 mb-4;
        }
        .prose h1 { @apply text-3xl; }
        .prose h2 { @apply text-2xl; }
        .prose h3 { @apply text-xl; }
        .prose p {
            @apply mb-4 leading-relaxed;
        }
        .prose a {
            @apply text-blue-600 hover:text-blue-800 underline;
        }
        .prose ul, .prose ol {
            @apply mb-4 pl-6;
        }
        .prose li {
            @apply mb-2;
        }
        .prose blockquote {
            @apply border-l-4 border-blue-500 pl-4 italic text-gray-700 my-4;
        }
        .prose code {
            @apply bg-gray-100 px-2 py-1 rounded text-sm;
        }
        .prose pre {
            @apply bg-gray-100 p-4 rounded-lg overflow-x-auto my-4;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">
                        TaxLegal Blog
                    </a>
                </div>
                
                <div class="flex items-center space-x-8">
                    <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-gray-900">Blog</a>
                    
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.blog.index') }}" class="text-gray-700 hover:text-gray-900">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} TaxLegal Blog. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>
