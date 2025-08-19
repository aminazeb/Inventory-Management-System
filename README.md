# Inventory

A Laravel-based Inventory Management System for managing products, inventory, sales, purchases, roles, and permissions. This project provides a robust backend API and a simple web interface for inventory operations.

## Features

- Product management
- Inventory tracking
- Sales and purchase records
- User authentication
- User authorization using Spatie Role/Permissions
- Export Inventory with Filters as Excel Sheet (Laravel Excel)
- Email Address Verification (Laravel Auth)
- Phone Number verificartion (Textbelt)
- AWS S3 for Cloud Storage (supabase)
- Role and permission management
- Orion/API endpoints
- Localization support (ar, en)

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


## Formatting
	php-cs-fixer fix app

## Usage

- Access the web interface at `http://localhost:8000`.
- API endpoints are available under `/api` and `/orion`.
- Authentication is required for all endpoints.
- Email verification is required for some endpoints.

## Testing

- Mailtrap for testing mails
- Run tests with:
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

N/A
