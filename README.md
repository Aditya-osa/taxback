# TaxLegal Blog Backend API

A Laravel-based backend API for the TaxLegal blog functionality, providing complete CRUD operations for blog posts, categories, tags, and comments.

## Features

- **Blog Posts Management**: Create, read, update, delete blog posts with rich content
- **Categories**: Organize posts into categories with color coding
- **Tags**: Tag posts with multiple tags for better organization
- **Comments**: Nested comment system with moderation
- **Authentication**: Token-based authentication for admin operations
- **SEO Ready**: Built-in SEO optimization features
- **Image Upload**: Featured image support for posts
- **Search & Filtering**: Advanced search and filtering capabilities

## Tech Stack

- **Backend**: Laravel 10.x
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **API Documentation**: RESTful API design

## Installation

### Prerequisites

- PHP 8.1+
- MySQL 5.7+ or 8.0+
- Composer
- Node.js (for frontend)

### Setup Instructions

1. **Clone and Install Dependencies**
   ```bash
   cd backend
   composer install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   # Update .env with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=taxlegal_blog
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

## API Endpoints

### Public Endpoints

#### Posts
- `GET /api/blog/posts` - List all published posts
- `GET /api/blog/posts/featured` - Get featured posts
- `GET /api/blog/posts/popular` - Get popular posts
- `GET /api/blog/posts/{post}` - Get a specific post

#### Categories
- `GET /api/blog/categories` - List all categories
- `GET /api/blog/categories/{category}` - Get category with posts

#### Tags
- `GET /api/blog/tags` - List all tags
- `GET /api/blog/tags/{tag}` - Get tag with posts

#### Comments
- `GET /api/blog/posts/{post}/comments` - Get post comments
- `POST /api/blog/posts/{post}/comments` - Create a comment

### Admin Endpoints (Authentication Required)

#### Posts Management
- `GET /api/admin/blog/posts` - List all posts (including drafts)
- `POST /api/admin/blog/posts` - Create a new post
- `GET /api/admin/blog/posts/{post}` - Get a specific post
- `PUT /api/admin/blog/posts/{post}` - Update a post
- `DELETE /api/admin/blog/posts/{post}` - Delete a post

#### Categories Management
- `GET /api/admin/blog/categories` - List all categories
- `POST /api/admin/blog/categories` - Create a new category
- `GET /api/admin/blog/categories/{category}` - Get a specific category
- `PUT /api/admin/blog/categories/{category}` - Update a category
- `DELETE /api/admin/blog/categories/{category}` - Delete a category

#### Tags Management
- `GET /api/admin/blog/tags` - List all tags
- `POST /api/admin/blog/tags` - Create a new tag
- `GET /api/admin/blog/tags/{tag}` - Get a specific tag
- `PUT /api/admin/blog/tags/{tag}` - Update a tag
- `DELETE /api/admin/blog/tags/{tag}` - Delete a tag

#### Comments Management
- `GET /api/admin/blog/comments` - List all comments
- `GET /api/admin/blog/comments/pending` - Get pending comments
- `PATCH /api/admin/blog/comments/{comment}/approve` - Approve a comment
- `PATCH /api/admin/blog/comments/{comment}/reject` - Reject a comment
- `DELETE /api/admin/blog/comments/{comment}` - Delete a comment

## Authentication

### Login Endpoint
```bash
POST /api/login
{
    "email": "admin@taxlegal.com",
    "password": "password"
}
```

### Using the Token
Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

## Default Admin Credentials

- **Email**: admin@taxlegal.com
- **Password**: password

## Database Schema

### Posts Table
- `id` - Primary key
- `title` - Post title
- `slug` - URL-friendly slug
- `excerpt` - Post excerpt
- `content` - Full post content
- `featured_image` - Path to featured image
- `status` - draft/published/archived
- `is_featured` - Boolean for featured posts
- `views` - View count
- `published_at` - Publication timestamp
- `author_id` - Foreign key to users
- `category_id` - Foreign key to categories

### Categories Table
- `id` - Primary key
- `name` - Category name
- `slug` - URL-friendly slug
- `description` - Category description
- `color` - Color code for UI
- `is_active` - Boolean for active status
- `sort_order` - Display order

### Tags Table
- `id` - Primary key
- `name` - Tag name
- `slug` - URL-friendly slug
- `description` - Tag description
- `color` - Color code for UI
- `is_active` - Boolean for active status

### Comments Table
- `id` - Primary key
- `content` - Comment content
- `status` - pending/approved/rejected
- `post_id` - Foreign key to posts
- `parent_id` - For nested comments
- `user_id` - Foreign key to users (optional)
- `guest_name` - Guest author name
- `guest_email` - Guest author email
- `guest_website` - Guest author website
- `ip_address` - Commenter IP address

## Configuration

Key configuration options in `config/blog.php`:

- `posts.per_page` - Number of posts per page (default: 12)
- `comments.auto_approve_users` - Auto-approve comments from authenticated users
- `upload.max_file_size` - Maximum file size for uploads (KB)
- `cache.enabled` - Enable caching for better performance

## Sample Data

The seeder includes:
- 3 sample users (admin@taxlegal.com, john@taxlegal.com, jane@taxlegal.com)
- 5 categories (Tax Law, Corporate Law, Legal Updates, Case Studies, Business Tips)
- 8 tags (Tax Planning, GST, Income Tax, etc.)
- 6 sample blog posts
- Multiple sample comments

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
php artisan pint
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Deployment

1. Set environment variables for production
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan migrate --force`
6. Set up proper file permissions for storage directory

## Security Features

- Token-based authentication
- Input validation and sanitization
- SQL injection protection
- CSRF protection
- Rate limiting on API endpoints
- File upload security

## API Response Format

All API responses follow this format:

```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 12,
        "total": 120
    }
}
```

Error responses:
```json
{
    "message": "Error description",
    "errors": {
        "field": ["Error message"]
    }
}
```

## Support

For support and questions, contact the development team at admin@taxlegal.com
