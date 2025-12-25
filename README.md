# Mini E-Commerce (Laravel + Sail)

A simple e-commerce backend built with **Laravel** using **Laravel Sail (Docker)**.

---

## Tech Stack

- PHP 8.x
- Laravel
- MySQL
- Docker & Laravel Sail

---

## Requirements

Make sure you have the following installed:

- Docker Desktop
- Git
- WSL (for Windows users)

---

## Setup & Run Project

### 1️Clone the repository
```bash
git clone <https://github.com/Abdalla194omar/mini-ecommerce-api.git>
cd mini-ecommerce

# Copy environment file
cp .env.example .env

# Install dependencies
./vendor/bin/sail composer install

# Generate application key
./vendor/bin/sail artisan key:generate

# Run migrations
./vendor/bin/sail artisan migrate

# Start the application
./vendor/bin/sail up -d

# Access the App
# Open your browser and visit
http://localhost
# You should see Mini Ecommerce is working 