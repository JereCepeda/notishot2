<?php

namespace Database\Seeders;

use App\Models\Note\Category;
use App\Models\Note\Note;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_note_user = User::where('email', 'redactor@apibase.com')->first();
        $categories = new Category();
        if (isset($created_note_user)) {
        Note::create([
            'user_id' => $created_note_user->id,
            'category_id' => $categories->where('id',1)->first()->id,
            'title' => 'Pandemia',
            'location' => 'Rosario, Santa Fe',
            'body' => 'La provincia de Santa Fe reportó 386 nuevos casos de Covid, 143 de ellos en Rosario'
        ]);
        Note::create([
            'user_id' => $created_note_user->id,
            'category_id' => $categories->where('id',2)->first()->id,
            'title' => 'Mundial en puerta',
            'location' => 'Qatar',
            'body' => 'Comienza la copa del mundo 2022, el que no salta abandonob'
        ]);}
    }
}
