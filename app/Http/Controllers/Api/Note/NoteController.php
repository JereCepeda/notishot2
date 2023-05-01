<?php

namespace App\Http\Controllers\Api\Note;

use App\Models\Note\Note;
use Illuminate\Support\Str;
use App\Models\Note\Resource;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Note\NoteResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Requests\Note\StoreResourceRequest;

class NoteController extends Controller
{
    protected $NoteResource = NoteResource::class;
    public $nota;
    public $user;
    public $formatos_permitidos =  array('jpg','jpeg' ,'png','gif');
        
    public function __construct(Note $nota)
    {
        $this->nota= $nota;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->user=Auth::user()->id;
        $notes = Note::with('resources')->get()->where('user_id',$this->user);
        $listaNotas = NoteResource::collection($notes);
        return response()->json([
            'data' => $listaNotas
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        $this->nota = Note::with('resources')->where('id',$note->id)->first();
        $noteResource = new NoteResource($this->nota);
        return response()->json([
            'data' => $noteResource
        ]);
    }
    public function showall()
    {
        $notes = Note::with('resources')->get();
        $listaNotas = NoteResource::collection($notes);
        return response()->json([
            'data' => $listaNotas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNoteRequest $request)
    {        
        $this->user=Auth::user()->id;
        $validate=Validator::make($request->all(),[$this->NoteResource]);
        $validate->fails() ? response()->json(['data' => $validate->errors()]) : $request['user_id'] = $this->user;
        $this->nota = Note::create($request->all());
        $resources = $request->file('resource');
        if($request->hasFile('resource')){
            foreach ($resources as $clave=>$file) {
                $archivo = $_FILES['resource']['name'];
                $ext= pathinfo($archivo[$clave], PATHINFO_EXTENSION);
                if(in_array($ext, $this->formatos_permitidos) ) {
                    $path = $file->storeAs('/',Carbon::now()->format('YmdHis').'.'.$ext,'public');
                    $last_id = Resource::max('id');$new_id = $last_id + 1;
                    $resource['id'] = $new_id;
                    $resource['note_id'] =  $this->nota->id;
                    $resource['type'] = '.'.$ext;
                    $resource['route']  = Storage::url($path);
                    $resource = Resource::create($resource)->toArray();
                }else{echo 'Error formato no permitido !!';}
            };        
        }
        $note = Note::with('resources')->find($this->nota->id);
        return response()->json(['data' => new NoteResource($note)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNoteRequest  $request
     * @param  \App\Models\Note\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->user=Auth::user()->id;
        $this->nota = Note::find($note->id);
        $this->nota->fill($request->all());
        $this->nota->save();
        $this->nota = $this->nota->with('resources')->first();
        
        return response(['Note' => new NoteResource($this->nota), 
        'message' => 'Update Note successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $nota = Note::where('id',$note->id)->first();
        $message = 'User deleted Succefully';
        is_null($nota->resource) ? $message .= ' and Resources deleted' : $nota->resource()->delete($nota->resource);
        isset($nota) ? $nota->delete() : $message = 'Error from delete Note'; 
        return $message;
    }
}