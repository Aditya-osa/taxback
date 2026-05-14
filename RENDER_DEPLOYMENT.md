# Render Deployment Guide for TaxLegal Backend

## 🚀 Quick Deployment Steps

### 1. Create GitHub Repository
1. Go to [github.com](https://github.com) and sign in
2. Click "New repository"
3. Name it: `taxlegal-backend`
4. Select "Public" (or Private if preferred)
5. Don't initialize with README (we already have code)
6. Click "Create repository"

### 2. Push Backend to GitHub
```bash
cd "d:\new -1 tax\taxlegal\backend"
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/YOUR_USERNAME/taxlegal-backend.git
git branch -M main
git push -u origin main
```

### 3. Deploy to Render

#### Option A: Using render.yaml (Recommended)
1. Go to [render.com](https://render.com)
2. Click "Sign up" or "Login" (use GitHub)
3. Click "New +" → "Blueprint"
4. Connect your GitHub account
5. Select your `taxlegal-backend` repository
6. Render will automatically detect the `render.yaml` file
7. Click "Apply" to deploy

#### Option B: Manual Setup
1. Go to [render.com](https://render.com)
2. Click "New +" → "Web Service"
3. Connect your GitHub repository
4. Configure:
   - **Name**: taxlegal-backend
   - **Region**: Oregon (or closest to you)
   - **Runtime**: Docker
   - **Branch**: main
   - **Root Directory**: . (leave empty)
   - **Dockerfile Path**: ./Dockerfile
5. Click "Create Web Service"

### 4. Create PostgreSQL Database
If not using render.yaml:
1. Click "New +" → "PostgreSQL"
2. Configure:
   - **Name**: taxlegal-db
   - **Database**: taxlegal_blog
   - **User**: taxlegal_user
   - **Region**: Same as web service
3. Click "Create Database"

### 5. Configure Environment Variables
In your web service dashboard, go to Settings → Environment Variables and add:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
DB_CONNECTION=pgsql
DB_HOST=your-db-host.render.com
DB_PORT=5432
DB_DATABASE=taxlegal_blog
DB_USERNAME=taxlegal_user
DB_PASSWORD=your-db-password
```

**To generate APP_KEY:**
```bash
php artisan key:generate --show
```

**Database credentials** can be found in your PostgreSQL database dashboard under "Connections".

### 6. Update Database Configuration
Since Render uses PostgreSQL by default, update your Laravel configuration:

In `config/database.php`, ensure PostgreSQL is configured (it should be by default in Laravel 10).

### 7. Run Database Migrations
The Dockerfile already includes migration commands, but you can also run them manually via Render's shell:
1. Go to your web service → "Shell"
2. Run: `php artisan migrate --force`

### 8. Test Your API
After deployment, your API will be available at:
- **Health check**: `https://taxlegal-backend.onrender.com/api/health`
- **Blog posts**: `https://taxlegal-backend.onrender.com/api/blog/posts`

## 📋 Configuration Files Created

### render.yaml
```yaml
services:
  - type: web
    name: taxlegal-backend
    runtime: docker
    plan: free
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_KEY
        generateValue: true
      - key: DB_CONNECTION
        value: pgsql
      - key: DB_HOST
        fromDatabase:
          name: taxlegal-db
          property: host
      - key: DB_PORT
        fromDatabase:
          name: taxlegal-db
          property: port
      - key: DB_DATABASE
        fromDatabase:
          name: taxlegal-db
          property: database
      - key: DB_USERNAME
        fromDatabase:
          name: taxlegal-db
          property: user
      - key: DB_PASSWORD
        fromDatabase:
          name: taxlegal-db
          property: password

databases:
  - name: taxlegal-db
    databaseName: taxlegal_blog
    user: taxlegal_user
    plan: free
```

### Dockerfile (Updated)
Updated to use Render's PORT environment variable:
```dockerfile
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

## 🔧 Troubleshooting

### Common Issues:

1. **Build fails**
   - Check PHP version in Dockerfile (requires PHP 8.1+)
   - Verify composer.json dependencies
   - Check Render build logs

2. **Database connection failed**
   - Verify PostgreSQL credentials in environment variables
   - Ensure DB_CONNECTION is set to `pgsql` (not `mysql`)
   - Check database is in the same region as web service

3. **APP_KEY error**
   - Generate a new key: `php artisan key:generate --show`
   - Add it to environment variables

4. **404 errors**
   - Ensure routes are properly configured in `routes/api.php`
   - Check Laravel application logs

5. **Migration errors**
   - Access the shell and run migrations manually
   - Check database permissions

### Logs:
- View logs in Render dashboard under "Logs"
- Use "Shell" to access the container and run commands

## 🌐 Update Frontend API URL

After deployment, update your React frontend to use the new Render API URL:

```javascript
// In your frontend code (e.g., Blog.jsx or api config)
const API_BASE_URL = 'https://taxlegal-backend.onrender.com/api';
```

Your frontend is already deployed at: https://effulgent-lily-066668.netlify.app/blog

## 💡 Important Notes

### Free Tier Limitations:
- Render free tier spins down after 15 minutes of inactivity
- Cold start can take 30-60 seconds
- Database has 90-day limit on free tier
- Consider upgrading to paid tier for production

### PostgreSQL vs MySQL:
- Render uses PostgreSQL by default (free tier)
- Your current .env.example uses MySQL
- Update DB_CONNECTION to `pgsql` for Render
- Laravel supports both, so no code changes needed

### CORS Configuration:
If your frontend is on a different domain, ensure CORS is configured in Laravel:
```php
// In config/cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['https://effulgent-lily-066668.netlify.app'],
```

## 📞 Support

If you encounter issues:
1. Check Render deployment logs
2. Verify environment variables
3. Ensure all dependencies are in composer.json
4. Check Laravel application logs in storage/logs
5. Access shell to debug directly

---

**Your backend will be live at:** `https://taxlegal-backend.onrender.com`
**Your frontend is already at:** `https://effulgent-lily-066668.netlify.app/blog`
