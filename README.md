# Inventory Management System

A Laravel-based Inventory Management System for managing products, inventory, sales, purchases, roles, and permissions. This project provides a robust backend API and a simple web interface for inventory operations.

## Features

- Product management (CRUD)
- Inventory tracking
- Sales and purchase records
- User authentication and authorization
- Role and permission management
- RESTful API endpoints
- Localization support (ar, en, es, fr)
- AWS S3 configured (supabase)

## Requirements

- PHP >= 8.1
- Composer
- Node.js & npm (for frontend assets)
- SQLite (default), MySQL, or PostgreSQL

## Installation

1. **Clone the repository:**
	```bash
	git clone https://github.com/aminazeb/Inventory-Management-System.git
	cd inventory
	```
2. **Install PHP dependencies:**
	```bash
	composer install
	```
3. **Install Node dependencies:**
	```bash
	npm install
	```
4. **Copy and configure environment:**
	```bash
	cp .env.example .env
	# Edit .env as needed
	```
5. **Generate application key:**
	```bash
	php artisan key:generate
	```
6. **Run migrations and seeders:**
	```bash
	php artisan migrate --seed
	```
7. **Build frontend assets:**
	```bash
	npm run build
	```
8. **Start the development server:**
	```bash
	php artisan serve
	```

## Usage

- Access the web interface at `http://localhost:8000`.
- API endpoints are available under `/api`.
- Authentication is required for most operations.

## Testing

Run tests with:
```bash
php artisan test
```

## Project Structure

- `app/Models/` — Eloquent models
- `app/Http/Controllers/` — API and web controllers
- `database/migrations/` — Database schema
- `database/seeders/` — Seed data
- `resources/views/` — Blade templates
- `routes/` — Route definitions

## License

This project is open-source and available under the [MIT License](LICENSE).
