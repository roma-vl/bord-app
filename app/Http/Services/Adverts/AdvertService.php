<?php

namespace App\Http\Services\Adverts;


use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\EditRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Http\Requests\Cabinet\Adverts\RejectRequest;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\LocatedArea;
use App\Models\LocatedCountry;
use App\Models\LocatedRegion;
use App\Models\LocatedVillage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    const int COUNTRY_ID = 1;
    public function create($userId, CreateRequest $request): Advert
    {
        $categoryId = $request->input('category_id');
        $regionId = $request->input('region_id');
        $areaId = $request->input('area_id');
        $villagesId = $request->input('villages_id');

        $user = User::findOrFail($userId);
        $category = Category::findOrFail($categoryId);
        $country = LocatedCountry::findOrFail(self::COUNTRY_ID) ?: null;
        $region = $regionId ? LocatedRegion::findOrFail($regionId) : null;
        $area = $areaId ? LocatedArea::findOrFail($areaId) : null;
        $villages = $villagesId ? LocatedVillage::findOrFail($villagesId) : null;

        return DB::transaction(function () use ($user, $category, $country, $region, $area, $villages, $request) {
            $advert = Advert::make([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'price' => $request->input('price'),
                'address' => $request->input('address'),
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->country()->associate($country);
            $advert->region()->associate($region);
            $advert->area()->associate($area);
            $advert->village()->associate($villages);
            $advert->saveOrFail();

            foreach ($category->allArrayAttributes() as $attribute) {
                $value = $request['attributes'][$attribute['id']] ?? null;
                if ($value !== null) {
                    $advert->value()->create([
                        'attribute_id' => $attribute['id'],
                        'value' => $value,
                    ]);
                }
            }

            return $advert;
        });
    }

    public function edit($id, EditRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $advert->update($request->only([
            'title',
            'content',
            'price',
            'address',
        ]));
    }

    public function editAttributes($id, AttributesRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            foreach ($advert->value as $value) {
                $value->delete();
            }

            foreach ($advert->category->allArrayAttributes() as $attribute) {
                $value = $request['attributes'][$attribute['id']] ?? null;
                if ($value !== null) {
                    $advert->value()->create([
                        'attribute_id' => $attribute['id'],
                        'value' => $value,
                    ]);
                }
            }
            $advert->update();
        });
    }

    public function addPhoto($id, PhotosRequest $photosRequest): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($photosRequest, $advert) {
            foreach ($photosRequest->file('photos') as $photo) {
                $advert->photo()->create([
                    'file' => $photo->store('adverts/' . $advert->id, 'public'),
                ]);
            }
        });
    }
    public function sendToModeration($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }


    public function moderate($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->moderate(Carbon::now());
    }

    public function reject($id, RejectRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $advert->reject($request['reason']);
    }
    public function expire(Advert $advert): void
    {
        $advert->expire();
    }

    public function close($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->close();
    }

    public function remove($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }


    public function getAdvert($id): Advert
    {
        return Advert::findOrFail($id);
    }
}
