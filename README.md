# Laravel 12 E-commerce Admin Panel  
[meet225/ecommerce](https://github.com/meet225/ecommerce)

A clean, modern, and high-performance mini-ecommerce administration panel built on Laravel 12. This panel is designed for efficient customer and order management, utilising AJAX for a seamless user experience.

---

## ✨ Features  
- Customer & Order Management: Comprehensive tools for managing both customer profiles and sales orders.  
- AJAX CRUD: Create, Read, Update and Delete operations handled asynchronously for smooth workflow.  
- DataTables Integration: Uses AJAX DataTables (server-side processing) for fast, efficient handling of large datasets in the order list.  
- Performance Focused: Implements Eloquent relationships with eager loading to eliminate N+1 query problems.  
- Modern UI: Built with Bootstrap 5.3 for a clean, responsive interface.  
- User Notifications: Provides intuitive user feedback using Toastr (for transient messages) and optionally SweetAlert2 (for confirmations).  
- Laravel 12 Ready: Follows the standard Laravel 12 directory structure and best practices.

---

## 🧰 Tech Stack  
| Component        | Version / Tech        |
|------------------|------------------------|
| Backend Framework| Laravel 12.x            |
| Language         | PHP 8.2+                |
| Database         | MySQL 8.0+ (or compatible)|
| Front-end Styling| Bootstrap 5.3           |
| JavaScript Utility| jQuery 3.6             |
| Data Grid        | DataTables 1.13         |

---

## ⚙️ Setup Instructions  
### 1️⃣ Clone the Repository  
```bash
git clone https://github.com/meet225/ecommerce.git
cd ecommerce

composer install
npm install
npm run dev


cp .env.example .env


APP_NAME="E-commerce Admin"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_admin
DB_USERNAME=root
DB_PASSWORD=


php artisan key:generate


php artisan migrate --seed


php artisan serve



app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── CustomerController.php
│   │   │   └── OrderController.php
│   └── Requests/
│       ├── StoreCustomerRequest.php
│       └── StoreOrderRequest.php
resources/
├── views/
│   ├── layouts/
│   │   └── admin_master.blade.php
│   ├── admin/
│   │   ├── customers/
│   │   └── orders/
│   └── welcome.blade.php
routes/
└── web.php
