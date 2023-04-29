<?php

namespace App\Http\Controllers\Api\Note;

use App\Models\Note\Note;
use App\Models\Note\Resource;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Note\NoteResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;

class NoteController extends Controller
{
    protected $NoteResource = NoteResource::class;
    public $nota;
    public $user;
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
        $userAuth= auth('api')->user()->id;
        $notes = Note::with('resources')->get()->where('user_id',$userAuth);
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
        $user = Auth::user()->id;
        $validate=Validator::make($request->all(),[$this->NoteResource]);
        $validate->fails() ? response()->json(['data' => $validate->errors()]) : $request['user_id'] = $user;
        dd($request->all());
        $note = Note::create($request->all());
        $formatos_permitidos =  array('jpg','jpeg' ,'png','gif');
        if($request->hasFile('resource')){
            $imagen= $request->file('resource');
            $archivo = $_FILES['resource']['name'];
            $ext= pathinfo($archivo, PATHINFO_EXTENSION);
            if(in_array($ext, $formatos_permitidos) ) {
                $path = $imagen->storeAs('/',Carbon::now()->format('YmdHis').'.'.$ext,'public');
                $resource['note_id'] = $note->id;
                $resource['type'] = '.'.$ext;
                $resource['route']  = Storage::url($path);
                $resource = Resource::create($resource);
                $note = Note::with('resources')->find($note->id);
            }else{echo 'Error formato no permitido !!';}
        };        
        return response()->json(['data' => new NoteResource($note)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreNoteRequest  $request
     * @param  \App\Models\Note\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNoteRequest $request, Note $note)
    {
        $user = Auth::user()->id;
        $validate=Validator::make($request->all(),[$this->NoteResource]);
        $validate->fails() ? response()->json(['data' => $validate->errors()]) : $request['user_id'] = $user;
        dd($request->all());
        $nota = Note::with('resources')->findOrFail($note->id);
        $formatos_permitidos =  array('jpg','jpeg' ,'png','gif');
        $nota->fill($request->all());
        $nota->save();
        if(isset($data['resource'])){
            $resource = Resource::where('user_id', $this->user->id)->first();
            $resource->update($data['resource'],$resource);
        }
        return response(['Note' => new NoteResource($nota), 
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
        //
    }
}
