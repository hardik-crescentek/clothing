<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'stock-adder']);
        Role::create(['name' => 'sales-person']);
        Role::create(['name' => 'dispatcher']);
        Role::create(['name' => 'client']);
        
    }
}
