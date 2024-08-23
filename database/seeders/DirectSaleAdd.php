<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class DirectSaleAdd extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a user with the role 'sales-person' and name 'Direct Sale'
        $user = User::create([
            'firstname' => 'Direct', 
            'lastname' => 'Sale',
            'phone' => '0987654321',
            'email' => 'direct.sale@example.com',
            'password' => Hash::make('DirectSale@123') // You can adjust the password as needed
        ]);

        $user->assignRole('sales-person'); 
    }
}
