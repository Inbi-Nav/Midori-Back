<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
   
    public function toArray(Request $request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];
        if ($request->user() && $request->user()->role === 'admin') {
            $data['created_at'] = $this->created_at->toISOString();
            $data['updated_at'] = $this->updated_at->toISOString();
            $data['provider_requested_at'] = $this->provider_requested_at?->toISOString();
            $data['email_verified_at'] = $this->email_verified_at?->toISOString();
        }
        return $data;
    }
}
