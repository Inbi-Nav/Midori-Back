<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderItemResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_amount' => (float) $this->total_amount,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
}
}
