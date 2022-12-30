<?php

namespace Database\Seeders;

use App\Models\Note\Note;
use App\Models\Note\Resource;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class ResouceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $search_note = Note::where('id',1)->first();
        if (isset($search_note)) {
            Resource::create([
                'note_id' => $search_note->id,
                'route' => Carbon::now()->format('YmdHis').'.jpg',
                'type' => '.jpg',
            ]);}
    }
}