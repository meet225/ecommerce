<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::truncate();

        $customers = [
            ['name' => 'Amit Sharma', 'email' => 'amit@yopmail.com', 'phone' => '9876543210'],
            ['name' => 'Priya Patel', 'email' => 'priya@yopmail.com', 'phone' => '9123456789'],
            ['name' => 'Rohit Mehta', 'email' => 'rohit@yopmail.com', 'phone' => '9012345678'],
            ['name' => 'Sneha Joshi', 'email' => 'sneha@yopmail.com', 'phone' => '9765432109'],
        ];

        foreach ($customers as $data) {
            Customer::create($data);
        }
    }
}
