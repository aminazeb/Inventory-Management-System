<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' =>  $this->product?->name ?: "",
            'color' =>   $this->product?->color ?: "",
            'image_url' =>   $this->product?->image_url ?: "",
            'price' =>   $this->product?->price ?: "",
            'quantity' =>  $this->quantity,
            'last_updated' =>  $this->last_stocked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
