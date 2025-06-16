# 📝 Task M - API App

This project was generated with [Laravel 12](https://laravel.com/) version 12.

A Laravel-based RESTful API for managing tasks with full CRUD operations, filtering, pagination, and token-based authentication.

## 📝 Development server

- If you dont have below requirment install, please install them first.
    - Normal setup
        Find ## Requirements mentioned here and install.

        ## ⚙️ Installation

        ```bash
        # Clone the repository
        git clone from repository
        cd your_project_root (root)

        # Install PHP dependencies
        composer install

        # Create environment file
        cp .env.example .env

        # Configure your .env file (DB connection, auth, mail, etc., )
        # Generate application key
        php artisan key:generate

        # Run database migrations
        php artisan migrate

        # (Optional) Seed the database
        php artisan db:seed

        # Start the development server
        php artisan serve

        Navigate to `http://127.0.0.1:port`. The application will automatically reload with the source files.

    - Containerization 
     - Setup/install Docker 

     - Create `Dockerfile` for install php dependency and other configurations.

     - Create `docker-compose.yml` for create services like TaskMApiApp, TaskMworker (for queue/background process : send emails) and Mysql_db app.

     - Then run `docker-compose up -d` for create/ serve the containers. 

## 🚀 Features

- ✅ User Authentication (JWT) - (User signup/signin)
- 🔄 Task CRUD (Create, Read, Update, Delete)
- 🔍 Filter tasks by status
- 📄 Paginated API responses
- 📦 Clean architecture (Controller → Service Layer → Model)
- 📊 Well-structured JSON responses
- 🔐 Protected API routes
- 📄 Database migrations and seeders.

## 📚 Requirements

- PHP ^8.2
- Laravel ^12
- Composer
- MySQL
- Laravel JWT for authentication

## 📦 Tech Stack

- [Laravel] - php framework

- [JWT] - for auth.

- [cors] - handle cors middleware.

- Future updates - x-api-key validation
