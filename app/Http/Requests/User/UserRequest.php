<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
   

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [            
            'nick_name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role' => 'exists:roles,id',
            'profile.name'=>'string',
            'profile.last_name' => 'string',
            'profile.cel_phone' => 'numeric',
            'profile.phone' => 'numeric',
            'profile.profile_photo' => 'string|nullable',
            'profile.facebook_url' => 'string|nullable',
            'profile.instagram_url' => 'string|nullable',
            'profile.twitter_url' => 'string|nullable',
            'profile.blog_personal_url' => 'string|nullable',
            'profile.city' => 'string',
            'profile.province' => 'string',
            'profile.country' => 'string',
            'profile.postal_code' => 'string'
        ];
    }
}
