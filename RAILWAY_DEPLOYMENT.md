# Railway Deployment Guide for TaxLegal Backend

## 🚀 Quick Deployment Steps

### 1. Create GitHub Repository
1. Go to [github.com](https://github.com) and sign in
2. Click "New repository"
3. Name it: `taxlegal-backend`
4. Select "Public"
5. Don't initialize with README (we already have code)
6. Click "Create repository"

### 2. Push Backend to GitHub
```bash
cd "d:\new -1 tax\taxlegal\backend"
git remote set-url origin https://github.com/YOUR_USERNAME/taxlegal-backend.git
git push --set-upstream origin main
```

### 3. Deploy to Railway

#### Option A: Web Dashboard (Recommended)
1. Go to [railway.app](https://railway.app)
2. Click "Sign up" or "Login" (use GitHub)
3. Click "New Project" → "Deploy from GitHub repo"
4. Select your `taxlegal-backend` repository
5. Railway will automatically detect it's a Laravel project
6. Click "Deploy Now"

#### Option B: Railway CLI
```bash
# Install Railway CLI (if not installed)
npm install -g @railway/cli

# Login to Railway
railway login

# Navigate to backend directory
cd "d:\new -1 tax\taxlegal\backend"

# Initialize Railway project
railway init

# Deploy
railway up
```

### 4. Configure Environment Variables
In Railway dashboard, go to your project → Settings → Variables and add:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
DB_CONNECTION=mysql
DB_HOST=containers-us-west-XXX.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=railway
DB_PASSWORD=YOUR_DB_PASSWORD
```

**To generate APP_KEY:**
```bash
php artisan key:generate --show
```

### 5. Run Database Migrations
In Railway dashboard, go to your project → Deployments → New Deployment → Add Command:
```bash
php artisan migrate --force
```

### 6. Test Your API
After deployment, your API will be available at:
- Health check: `https://your-app-name.railway.app/api/health`
- Blog posts: `https://your-app-name.railway.app/api/blog/posts`

## 📋 Configuration Files Created

### railway.json
```json
{
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT",
    "healthcheckPath": "/api/health"
  }
}
```

### Procfile
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

### Health Check Route
Added `/api/health` endpoint for Railway health monitoring.

## 🔧 Troubleshooting

### Common Issues:
1. **Build fails**: Check PHP version (requires PHP 8.1+)
2. **Database connection failed**: Verify Railway database credentials
3. **APP_KEY error**: Generate a new key with `php artisan key:generate --show`
4. **404 errors**: Ensure routes are properly configured

### Logs:
- Check Railway deployment logs in the dashboard
- Use `railway logs` command for CLI

## 🌐 Update Frontend API URL

After deployment, update your React frontend to use the new Railway API URL:

```javascript
// In Blog.jsx or wherever API is called
const API_BASE_URL = 'https://your-app-name.railway.app/api';
```

## 📞 Support

If you encounter issues:
1. Check Railway logs
2. Verify environment variables
3. Ensure all dependencies are in composer.json
4. Check Laravel application logs

---

**Your backend will be live at:** `https://your-app-name.railway.app`
