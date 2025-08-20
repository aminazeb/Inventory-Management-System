<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'image_url' => $this->image_url,
            'price' => $this->price,
            'meta' => $this->meta,
            'status' => $this->deleted_at ? 'archived' : 'active',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
