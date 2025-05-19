<?php

namespace App\Http\Resources\Api\Advert;

use App\Models\Adverts\Attribute;
use App\Models\Adverts\Photo;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AdvertDetailResource",
 *     type="object",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="name", type="string", example="Іван"),
 *         @OA\Property(property="phone", type="string", example="+380991234567")
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=4),
 *         @OA\Property(property="name", type="string", example="Авто")
 *     ),
 *     @OA\Property(
 *         property="region",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=22973),
 *         @OA\Property(property="name", type="string", example="Київ")
 *     ),
 *     @OA\Property(property="title", type="string", example="BMW E39 2003"),
 *     @OA\Property(property="content", type="string", example="Машина в хорошому стані..."),
 *     @OA\Property(property="price", type="number", format="float", example=15000),
 *     @OA\Property(property="address", type="string", example="вул. Шевченка 12"),
 *     @OA\Property(
 *         property="date",
 *         type="object",
 *         @OA\Property(property="published", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *         @OA\Property(property="expires", type="string", format="date-time", example="2024-02-01T12:00:00Z")
 *     ),
 *     @OA\Property(
 *         property="values",
 *         type="array",
 *
 *         @OA\Items(
 *             type="object",
 *
 *             @OA\Property(property="name", type="string", example="Рік"),
 *             @OA\Property(property="value", type="string", example="2003")
 *         )
 *     ),
 *     @OA\Property(
 *         property="photos",
 *         type="array",
 *
 *         @OA\Items(type="string", example="/uploads/photos/1.jpg")
 *     )
 * )
 */
class AdvertDetailResource extends JsonResource
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
            'content' => $this->content,
            'price' => $this->price,
            'address' => $this->address,
            'date' => [
                'published' => $this->published_at,
                'expires' => $this->expires_at,
            ],
            'values' => $this->category && $this->category->attributes
                ? $this->category->attributes->map(function (Attribute $attribute) {
                    return [
                        'name' => $attribute->name,
                        'value' => $this->getValue($attribute->id),
                    ];
                })->toArray() : [],
            'photos' => $this->photo ? $this->photo->map(function (Photo $photo) {
                return $photo->file;
            })->toArray() : [],

        ];
    }
}
