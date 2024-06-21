<?php



namespace Database\Seeders;



use Illuminate\Database\Seeder;

use App\User;

use Illuminate\Support\Facades\Hash;



class AdminUserSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

        $user = User::create([

            'firstname' => 'Super', 

            'lastname' => 'Admin',

            'phone' => '1234567890',

        	'email' => 'super@admin.com',

        	'password' => Hash::make('Super@dmin')

        	// 'password' => bcrypt('Super@dmin')

        ]);  

        $user->assignRole('super-admin');



        factory(User::class, 5)->create()->each(function ($user) {

            $user->assignRole('client');

        });

        factory(User::class, 5)->create()->each(function ($user) {

            $user->assignRole('stock-adder');

        });

        factory(User::class, 5)->create()->each(function ($user) {

            $user->assignRole('sales-person');

        });

        factory(User::class, 5)->create()->each(function ($user) {

            $user->assignRole('dispatcher');

        });



    }

}

