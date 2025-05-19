<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->email_verified_at,
            'avatar_url' => $this->avatar_url,
            'locale' => $this->locale,
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->diffForHumans() : null,
            'roles' => $this->roles->pluck('name'),
        ];
    }
}
