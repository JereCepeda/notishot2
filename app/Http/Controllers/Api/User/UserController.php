<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User\User;
use App\Models\User\Profile;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userResource = UserResource::class;
    protected $user;
    
    function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function index($toFind = null)
    {
        switch ($toFind) {
            case 'active':
                $this->user->where('deleted_at', null)->where('role_id', 1)->get();
                return response()->json(['data' =>$this->user]);
            break;
            case 'deleted':
                return response()->json([
                    'data' =>$this->user->onlyTrashed()->where('role_id', 1)->get()
                ]);
            break;
            default:
                return response()->json([
                    'data' =>$this->user->where('role_id', '!=', 1)->get()
                ]);                
                break;
        }
    }
    public function show($id)
    {
        $userShow = $this->user->where('id',$id)->first();
        return response()->json(['data' => new UserResource($userShow)]);
    }
    public function store(UserRequest $request ) {
        $data = $request->all();
        $validate=Validator::make($data,[$this->userResource]);
        if($validate->fails()){
            return response()->json(['data' => $validate->errors()]);
        }
        $this->user = User::create($data);
        if(isset($data['profile']))
            {
                $profile = $data['profile'];
                $profile['user_id']  = $this->user->id;
                Profile::create($profile);
            }
        return response()->json([
            'error'=> false,
            'data'=> null,
            'httpCod'=>http_response_code(),
            'message' => new UserResource($this->user)], 200);
    }
    
    public function update(UpdateUserRequest $urequest,$data) 
    {   
        $this->user= User::where('id',$data)->first(); 
        $this->user->fill($urequest->all());
        $this->user->save();
        if(isset($data['profile'])){
            $profile = Profile::where('user_id', $this->user->id)->first();
            $profile->update($data['profile'],$profile);
        }
        return response(['User' => $urequest->all(), 
        'message' => 'Update User successfully'], 200);
    }

    public function destroy($id)
    {
        $user = User::where('id',$id)->first();
        $message = 'User deleted Succefully';
        is_null($user->profile) ? $message .= ' and Profile deleted' : $user->profile()->delete($user->profile);
        isset($user) ? $user->delete() : $message = 'Error from delete User'; 
        return $message;
    }
}