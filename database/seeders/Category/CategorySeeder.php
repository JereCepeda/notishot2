<?php

namespace Database\Seeders\Category;
use App\Models\Note\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['description' => "Copa Mundial Qatar 2022",'status' => true]);
        Category::create(['description' => "Covid",'status' => true]);
    }
}
