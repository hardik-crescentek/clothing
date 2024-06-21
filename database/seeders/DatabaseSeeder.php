<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Category;
use App\Color;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(AdminUserSeeder::class);
        Category::create(['name' => 'Category 1', 'slug' => 'category-1','parent_id' => 0]);
        Category::create(['name' => 'Category 2', 'slug' => 'category-2','parent_id' => 0]);
        Category::create(['name' => 'Sub Cat 1-1', 'slug' => 'sub-cat-1-1','parent_id' => 1]);

        Color::create(['name' => 'Black', 'code' => '#000000']);  
        Color::create(['name' => 'Red', 'code' => '#FF0000']);  
        Color::create(['name' => 'Green', 'code' => '#00FF00']);  
        Color::create(['name' => 'Blue', 'code' => '#0000FF']);  
        Color::create(['name' => 'Gray', 'code' => '#CCCCCC']);  
    }
}
