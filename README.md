# Laravel Project Setup

This guide outlines the steps to set up and run the Laravel project locally.

## Requirements

- PHP >= 8.x
- Composer
- MySQL or compatible database
- Node.js and npm (for frontend assets, if applicable)

## Installation

Follow the steps below to get started:

1. **Clone the repository**
   ```bash
   git clone <project-url>
   cd <project-folder>

2. **Install PHP dependencies**
   ```bash
   composer install
   
3. **Copy .env file**
   ```bash
   cp .env.example .env

4. **Update .env with your database credentials**
   ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

5. **Generate application key**
   ```bash
   php artisan key:generate

6. **Create session table**
   ````bash
   php artisan session:table

7. **Run database migrations**
   ````bash
   php artisan migrate

8. **Serve the application**
   ````bash
   php artisan serve
   live


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
