<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Customers Module (using Resource routing)
    Route::resource('customers', CustomerController::class);

    // Orders Module (custom routes)
    Route::controller(OrderController::class)->group(function () {
        Route::get('orders', 'index')->name('orders.index');
        Route::get('orders/create', 'create')->name('orders.create');
        Route::post('orders', 'store')->name('orders.store');
        Route::get('orders/{order}', 'show')->name('orders.show');
        Route::patch('orders/{order}/status', 'updateStatus')->name('orders.updateStatus');
    });
});