<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Database\Seeders\Note\NoteSeeder;
use Database\Seeders\User\UserTableSeeder;

use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\Resource\ResourceSeeder;

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
