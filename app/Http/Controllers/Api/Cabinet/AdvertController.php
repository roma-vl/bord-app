<?php

namespace App\Http\Controllers\Api\Cabinet;

use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\EditRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Http\Resources\Api\Advert\AdvertDetailResource;
use App\Http\Resources\Api\Advert\AdvertListResource;
use App\Http\Services\Adverts\AdvertService;
use App\Models\Adverts\Advert;
use Auth;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class AdvertController
{
    public function __construct(private readonly AdvertService $advertService){}

    public function index(): AnonymousResourceCollection
    {
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return AdvertListResource::collection($adverts);
    }

    public function show(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        return new AdvertDetailResource($advert);
    }

    public function store(CreateRequest $request): JsonResponse
    {
        $advert = $this->advertService->create(
            Auth::id(),
            $request
        );

        return (new AdvertDetailResource($advert))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(EditRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->edit($advert->id, $request);
        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function attributes(AttributesRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->editAttributes($advert->id, $request);
        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function photos(PhotosRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->addPhoto($advert->id, $request);
        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function send(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->sendToModeration($advert->id);
        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function close(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->advertService->close($advert->id);
        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function destroy(Advert $advert): JsonResponse
    {
        $this->checkAccess($advert);
        $this->advertService->remove($advert->id);
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function checkAccess(Advert $advert): void
    {
        if (!Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }
}
