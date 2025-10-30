# Laravel-Coding-Standards

## Prerequisites
Make sure you have the following installed:

- PHP ≥ 8.1
- Composer
- MySQL or SQLite for database
- Xampp

## Step 1: Copy .env.example to .env:
```
cp .env.example .env
```

The .env file holds the environment variables like database credentials, API keys, etc.

## Step 2: Install PHP dependencies:
```
composer install
```

This command installs all PHP dependencies listed in the composer.json file.

## Step 3: Generate the application key:
```
php artisan key:generate
```

This generates a new key in the .env file that is used for encryption.

## Step 4: Run database migrations (if necessary):
```
php artisan migrate
```

This command applies the database migrations and sets up the necessary database structure.

## Step 5: Serve the application:
```
php artisan serve
```

Starts the application on a local server (usually at http://localhost:8000).

