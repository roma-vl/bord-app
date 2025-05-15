<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Profile',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(
            property: 'name',
            properties: [
                new OA\Property(property: 'first', type: 'string'),
                new OA\Property(property: 'last', type: 'string'),
            ],
            type: 'object'
        ),
        new OA\Property(property: 'email', type: 'string'),
        new OA\Property(
            property: 'phone',
            properties: [
                new OA\Property(property: 'number', type: 'string'),
                new OA\Property(property: 'varified', type: 'boolean'),
            ],
            type: 'object'
        ),
        new OA\Property(property: 'locale', type: 'string'),
        new OA\Property(property: 'avatar_url', type: 'string'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => [
                'first' => $this->name,
                'last' => $this->first_name
            ],
            'email' => $this->email,
            'phone' => [
                'number' => $this->phone,
                'varified' => $this->phone_verified,
            ],
            'locale' => $this->locale,
            'avatar_url' => $this->avatar_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
