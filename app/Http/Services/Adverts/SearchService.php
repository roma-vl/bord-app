<?php

namespace App\Http\Services\Adverts;


use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\EditRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Http\Requests\Cabinet\Adverts\RejectRequest;
use App\Http\Requests\Cabinet\Adverts\UpdateRequest;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchService
{
    public function __construct(
        private readonly Client $client
    ) {}

    public function search(string $query, int $page = 1, int $perPage = 1): array
    {
        $from = ($page - 1) * $perPage;

        $params = [
            'index' => 'adverts',
            'body' => [
                'from' => $from,
                'size' => $perPage,
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title^2', 'content'],
                        'fuzziness' => 'AUTO'
                    ]
                ]
            ]
        ];

        $response = $this->client->search($params);

        $total = $response['hits']['total']['value'] ?? 0;
        $lastPage = (int) ceil($total / $perPage);

        $baseUrl = url()->current(); // або route('search') якщо використовуєш іменовані маршрути
        $queryParams = ['q' => $query];

        $makePageUrl = fn($pageNum) => $pageNum > 0 && $pageNum <= $lastPage
            ? $baseUrl . '?' . http_build_query(array_merge($queryParams, ['page' => $pageNum]))
            : null;

        $links = [];

        // Попередня сторінка
        $links[] = [
            'url' => $makePageUrl($page - 1),
            'label' => 'Назад',
            'active' => false
        ];

        // Сторінки
        for ($i = 1; $i <= $lastPage; $i++) {
            $links[] = [
                'url' => $makePageUrl($i),
                'label' => (string) $i,
                'active' => $i === $page
            ];
        }

        // Наступна сторінка
        $links[] = [
            'url' => $makePageUrl($page + 1),
            'label' => 'Вперед',
            'active' => false
        ];

        return [
            'items' => array_map(fn($hit) => $hit['_source'], $response['hits']['hits']),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage,
            'links' => $links
        ];
    }




}
