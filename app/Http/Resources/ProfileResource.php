<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'bio' => $this->bio,
            'avatar' => $this->avatar
                ? asset('storage/' . $this->avatar)
                : null,

            'address' => $this->address,
            'country' => $this->country,
            'city' => $this->city,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'phone'=>$this->phone,
            'user'=>new UserResource($this->user),
            'created_at' => $this->created_at,
        ];
    }
}
