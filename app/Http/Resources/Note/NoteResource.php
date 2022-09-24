<?php

namespace App\Http\Resources\Note;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\Note\CategoryResource;
use App\Http\Resources\Note\ResourceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'title' =>$this->title,
            'location' => $this->location,
            'user' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
            'resources' => ResourceResource::collection($this->whenLoaded('resources'))
        ];
    }
}
