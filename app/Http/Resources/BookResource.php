<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'id'=> $this->id,
            'name'=> $this->name,
            'details'=> $this->details,
            'numberOfPages'=> $this->numberOfPages,
            'price'=> number_format($this->price),
            'cover'=> asset("storage/" . $this->cover) ,
            'category'=> $this->cat,
            'author'=> $this->author,
            'add time'=> $this->created_at,
        ];
    }
}
