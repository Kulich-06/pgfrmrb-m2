<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClotchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) 
    { 
        return [ 
            'name' => $this->name, 
            'category_id' => $this->category_id,
            'category'=>$this->category->name, 
            'color'=>$this->color->name, 
            'season'=>$this->season->name, 
            'img' => $this->img, 
            'size' => $this->size, 
        
        ]; 
    }
}
