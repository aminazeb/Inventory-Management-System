<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'supplier' => $this->supplier,
            'manufacturer' => $this->manufacturer,
            'cost_per_unit' => $this->cost_per_unit,
            'amount' => $this->amount,
            'product' =>  [
                'id' => $this->product_id,
                'name' => $this->product?->name,
            ],
            'quantity' => $this->quantity,
            'meta' => $this->meta,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
