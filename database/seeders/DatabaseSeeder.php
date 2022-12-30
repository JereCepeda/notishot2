<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\NoteSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ResourceSeeder;
use Database\Seeders\User\UserTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            CategorySeeder::class,
            NoteSeeder::class,
            ResourceSeeder::class, 
        ]);
    }
}
