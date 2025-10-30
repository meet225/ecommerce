<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::truncate();
        OrderItem::truncate();

        $customers = Customer::all();
        $products = Product::all();

        foreach ($customers as $customer) {
            for ($i = 1; $i <= 2; $i++) {
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_date' => Carbon::now()->subDays(rand(1, 10)),
                    'status' => ['Pending', 'Completed', 'Cancelled'][rand(0, 2)],
                    'total_amount' => 0,
                ]);

                $selectedProducts = $products->random(rand(1, 3));
                $total = 0;

                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 3);
                    $price = $product->price;
                    $total += $price * $qty;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $price,
                    ]);

                    $product->decrement('stock_quantity', $qty);
                }

                $order->update(['total_amount' => $total]);
            }
        }

    }
}
