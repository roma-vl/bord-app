<?php

namespace App\Http\Services\Adverts;

use App\Events\Advert\AdvertChanged;
use App\Events\Advert\ModerationPassed;
use App\Http\Requests\Admin\Adverts\RejectRequest;
use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\EditRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Http\Requests\Cabinet\Adverts\UpdateRequest;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\Location;
use App\Models\User;
use App\Notifications\Advert\ModerationRejectNotification;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    const int PER_PAGE = 5;

    public function create($userId, CreateRequest $request): Advert
    {
        $categoryId = $request->input('category_id');
        $regionId = $request->input('region_id');

        $user = User::findOrFail($userId);
        $category = Category::findOrFail($categoryId);
        $region = $regionId ? Location::findOrFail($regionId) : null;

        return DB::transaction(function () use ($user, $category, $region, $request) {
            $advert = Advert::make([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'price' => $request->input('price'),
                'address' => $request->input('address'),
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);
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

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $advert->photo()->create([
                        'file' => $image->store('adverts/'.$advert->id, 'public'),
                    ]);
                }
            }

            return $advert;
        });
    }

    public function edit($id, EditRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $oldPrice = $advert->price;
        $advert->update($request->only([
            'title',
            'content',
            'price',
            'address',
        ]));

        //        if ($oldPrice != $advert->price) {
        //            foreach ($advert->favorites()->create() as $user) {
        //                \Mail::to($user->email)->queue(new AdvertPriceChanged($user, $advert, $oldPrice));
        //            }
        //        }
    }

    public function update(UpdateRequest $request, Advert $advert): void
    {
        DB::transaction(function () use ($advert, $request) {
            $advert->update($request->only([
                'title',
                'content',
                'price',
                'address',
                'category_id',
                'region_id',
            ]));

            $advert->value()->delete();

            foreach ($advert->category->allArrayAttributes() as $attribute) {
                $value = $request['attributes'][$attribute['id']] ?? null;
                if ($value !== null) {
                    $advert->value()->create([
                        'attribute_id' => $attribute['id'],
                        'value' => $value,
                    ]);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $advert->photo()->create([
                        'file' => $image->store('adverts/'.$advert->id, 'public'),
                    ]);
                }
            }
        });
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
                    'file' => $photo->store('adverts/'.$advert->id, 'public'),
                ]);
            }
        });
    }

    public function sendToModeration($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    public function moderate(Advert $advert): void
    {
        if (! $advert->isActive()) {
            $advert->moderate(Carbon::now());
        }

        event(new ModerationPassed($advert));
        event(new AdvertChanged($advert));
    }

    public function reject(Advert $advert, RejectRequest $request): void
    {
        $advert->reject($request['reject_reason']);

        $advert->user->notify(new ModerationRejectNotification($advert, $request['reject_reason']));
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

    public function getLatest(): Collection
    {
        return Advert::query()
            ->where('status', 'active')
            ->with(['firstPhoto', 'favorites'])
            ->latest()
            ->take(4)
            ->get();
    }

    public function getVip(): Collection
    {
        return Advert::query()
            ->where('status', 'active')
            ->where('premium', 1)
            ->with(['firstPhoto', 'favorites'])
            ->latest()
            ->take(4)
            ->get();
    }

    public function getAdvertForModeration()
    {
        return Advert::query()
            ->where('status', Advert::STATUS_MODERATION)
            ->orderByDesc('created_at')
            ->paginate(self::PER_PAGE);
    }
}
