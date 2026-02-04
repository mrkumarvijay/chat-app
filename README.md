# Laravel 12 Chat Application

A real-time chat application built with Laravel 12, Vue.js, Laravel Reverb, and Laravel Echo. Features user authentication, private messaging, and real-time notifications.

## 🚀 Features

- **User Authentication**: Secure registration and login with Laravel Passport
- **Real-time Messaging**: Instant message delivery using Laravel Reverb and Echo
- **Private Channels**: Secure private messaging between users
- **Live Notifications**: Real-time notification system with toast popups
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5
- **Message History**: Persistent message storage with timestamps
- **User Online Status**: Presence channels for online/offline indicators

## 🛠 Tech Stack

### Backend
- **Laravel 12** - PHP framework
- **Laravel Passport** - OAuth2 authentication
- **Laravel Reverb** - WebSocket server
- **Laravel Echo** - Real-time event broadcasting
- **MySQL** - Database

### Frontend
- **Bootstrap 5** - CSS framework
- **Vanilla JavaScript** - No framework dependencies
- **Vite** - Build tool

## 📋 Prerequisites

- PHP 8.2+
- Composer
- MySQL 8.0+
- Git

## 🚀 Quick Start

### 1. Clone and Install

```bash
# Clone the repository
git clone <your-repo-url>
cd chat-application

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Build frontend assets
npm run dev
```

### 2. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_application
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE chat_application;"

# Run migrations
php artisan migrate

# Generate Passport keys
php artisan passport:install
```

### 4. Start Development Servers

```bash
# Start Laravel development server
php artisan serve

# Start Reverb WebSocket server (in new terminal)
php artisan reverb:start
```

### 5. Access Application

- **Frontend**: http://localhost:8000
- **API**: http://localhost:8000/api

## 📖 API Documentation

See [docs/API.md](docs/API.md) for complete API documentation with examples.

## 🔧 Configuration

### Broadcasting Setup

The application uses Laravel Reverb for WebSocket connections. Configure in `.env`:

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_APP_ID=your-app-id
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=https
```

### Authentication

Uses Laravel Passport for OAuth2 authentication. The API uses Bearer tokens for authentication.

## 🔍 Troubleshooting

### Common Issues

#### 1. `/broadcasting/auth` returns 403
**Cause**: Missing or invalid authentication token
**Solution**: 
- Ensure token is stored in localStorage as 'token'
- Check Authorization header format: `Bearer <token>`
- Verify API guard is set to 'passport' in `config/auth.php`

#### 2. WebSocket connection fails
**Cause**: Reverb server not running or misconfigured
**Solution**:
- Start Reverb server: `php artisan reverb:start`
- Check Reverb configuration in `.env`
- Verify firewall allows port 8080

#### 3. Real-time messages not appearing
**Cause**: Echo not properly initialized or channel authorization failed
**Solution**:
- Check browser console for errors
- Verify token is present in localStorage
- Ensure channel name format: `chat.{userId}`

#### 4. `app.js` 404 error
**Cause**: Vite build not completed or assets not compiled
**Solution**:
- Run `npm run dev` to build assets
- Check `public/build/` directory exists
- Verify Vite configuration in `vite.config.js`

### Debug Commands

```bash
# Clear all caches
php artisan optimize:clear

# Check broadcasting configuration
php artisan config:show broadcasting

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check Passport installation
php artisan passport:client --help
```

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- Laravel Framework
- Laravel Reverb
- Laravel Echo
- Bootstrap 5
- Pusher (WebSocket provider)
