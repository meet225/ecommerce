🛍️ Laravel 12 E-commerce Admin Panel

A clean, modern, and high-performance mini e-commerce administration panel built on Laravel 12. This panel is designed for efficient Customer and Order Management utilizing AJAX for a seamless user experience.

✨ Features

Customer & Order Management: Comprehensive tools for managing both customer profiles and sales orders.

AJAX CRUD: Create, Read, Update, and Delete operations handled asynchronously for a smooth workflow.

DataTables Integration: Uses AJAX DataTables (server-side processing) for fast, efficient handling of large datasets in the order list.

Performance Focused: Implements Eloquent relationships with eager loading to eliminate N+1 query problems.

Modern UI: Built with Bootstrap 5.3 for a clean, responsive interface.

User Notifications: Provides intuitive user feedback using Toastr (for transient messages) and SweetAlert (for confirmations).

Laravel 12 Ready: Follows the standard Laravel 12 directory structure and best practices.

🛠️ Tech Stack

Component

Version / Tech

Backend Framework

Laravel 12.x

Language

PHP 8.2+

Database

MySQL 8.0+

Frontend Styling

Bootstrap 5.3

JavaScript Utility

jQuery 3.6

Data Grid

DataTables 1.13

🚀 Setup Instructions

Follow these steps to get the application running locally.

1️⃣ Clone the Repository

git clone [https://github.com/yourusername/laravel12-ecommerce-admin.git](https://github.com/yourusername/laravel12-ecommerce-admin.git)
cd laravel12-ecommerce-admin


2️⃣ Install Dependencies

composer install
npm install
npm run dev


3️⃣ Create Environment File

Copy the example environment file and update your database credentials.

cp .env.example .env


Ensure your .env file contains the following (adjusting database name/credentials as needed):

APP_NAME="Ecommerce Admin"
APP_ENV=local
APP_DEBUG=true
APP_URL=[http://127.0.0.1:8000](http://127.0.0.1:8000)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_admin
DB_USERNAME=root
DB_PASSWORD=


4️⃣ Generate Key

php artisan key:generate


5️⃣ Run Migrations & Seeders

This command will set up the database schema and populate it with sample customer and order data.

php artisan migrate --seed


6️⃣ Serve the Application

php artisan serve


Access the admin panel in your browser: 👉 http://127.0.0.1:8000

📂 Directory Overview

Key files and folders for the admin panel logic:

Path

Purpose

app/Http/Controllers/CustomerController.php

Handles customer CRUD operations.

app/Http/Controllers/OrderController.php

Handles order management, list rendering, and status updates.

app/Http/Requests/*Request.php

Form request validation for customer and order data.

resources/views/customers/

Blade views for customer interface.

resources/views/orders/

Blade views (like index.blade.php) for the order list.

routes/web.php

Defines the main application routes.

🔐 Routes Overview

Route

Description

/

Welcome page showing customer orders summary.

/admin/customers

List & manage customers.

/admin/orders

View & manage orders (where the AJAX DataTables are implemented).

💡 Common Issues & Fixes

Problem

Fix

toastr is not defined

Ensure you have added the Toastr JS link: <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> after jQuery.

Class "App\Http\Controllers\Controller" not found

Run composer dump-autoload to regenerate the class map.

Migrations not running

Double-check your database credentials in the .env file.

🧑‍💻 Developer Notes

All routes are protected with the auth middleware (standard admin login logic).

AJAX CRUD operations return JSON responses, which are then used by the frontend to trigger Toastr/SweetAlert feedback.

📜 License

MIT © 2025