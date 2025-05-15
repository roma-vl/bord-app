<?php

namespace App\Http\Resources\Api\Advert;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdvertListResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(
            property: 'user',
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Іван'),
                new OA\Property(property: 'phone', type: 'string', example: '+380991234567')
            ],
            type: 'object'
        ),
        new OA\Property(
            property: 'category',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 4),
                new OA\Property(property: 'name', type: 'string', example: 'Авто')
            ],
            type: 'object'
        ),
        new OA\Property(
            property: 'region',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 22973),
                new OA\Property(property: 'name', type: 'string', example: 'Київ')
            ],
            type: 'object'
        ),
        new OA\Property(property: 'title', type: 'string', example: 'Продаю BMW E39'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 15000),
        new OA\Property(property: 'date', type: 'string', format: 'date-time', example: '2024-01-01T12:00:00Z')
    ],
    type: 'object'
)]

class AdvertListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'name' => $this->user->name,
                'phone' => $this->user->phone,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'region' => $this->region ? [
                'id' => $this->region->id,
                'name' => $this->region->name,
            ] : [],
            'title' => $this->title,
            'price' => $this->price,
            'date' => $this->published_at,
//            'photo' => $this->photos->first(),
        ];
    }
}
