<?php

namespace App\Http\Controllers\Api\Adverts;

use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Resources\Api\Advert\AdvertDetailResource;
use App\Http\Resources\Api\Advert\AdvertListResource;
use App\Http\Services\Adverts\SearchService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\Location;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class AdvertController
{
    public function __construct(
        private SearchService $searchService
    ){}

    #[OA\Get(
        path: '/api/v1/adverts',
        summary: 'Список оголошень з фільтрами',
        security: [['Bearer' => []], ['OAuth2' => []], ['bearerAuth' => []]],
        tags: ['Adverts'],
        parameters: [
            new OA\Parameter(
                name: 'category',
                description: 'ID категорії',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 4)
            ),
            new OA\Parameter(
                name: 'region',
                description: 'ID регіону',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 22973)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Кількість оголошень на сторінку',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Сторінка пагінації',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: ResponseAlias::HTTP_OK,
                description: 'Список оголошень',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/AdvertListResource')
                )
            )
        ]
    )]

    public function index(SearchRequest $request)
    {
        $region = $request->get('region') ? Location::findOrFail($request->get('region')) : null;
        $category = $request->get('category') ? Category::findOrFail($request->get('category')) : null;

        $r = $region && $region->slug ? $region->slug . '/' : '';
        $urlPath = $r . $region && $category->slug ?: '';
        $result = $this->searchService->search($category, $region, $request, $urlPath, $request->get('page', 1), $request->get('rer_page', 1));

        return AdvertListResource::collection($result->adverts);
    }

    public function show(Advert $advert)
    {
        if (!($advert->isActive())) {
            abort(403);
        }

        return new AdvertDetailResource($advert);
    }
}
