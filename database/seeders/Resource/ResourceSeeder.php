<?php
namespace Database\Seeders\Resource;

use App\Models\Note\Note;
use App\Models\Note\Resource;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
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
                'route' =>"storage/20230425024624.jpg",
                'type' => '.jpg',
            ]);}
    }
}