<?php

namespace App\Http\Resources\Note;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
             'id' => $this->id,
             'type'  => $this->type,
             'route'  => $this->route,
            ];
    }
}
