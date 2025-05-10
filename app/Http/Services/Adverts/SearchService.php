<?php
namespace App\Http\Services\Adverts;

use App\Http\Requests\Adverts\SearchRequest;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Category;
use App\Models\Location;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Query\Expression;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class SearchService
{
    public function __construct(
        private Client $client
    ) {}

    public function search(?Category $category, ?Location $region, SearchRequest $request, int $page = 1, int $perPage = 1): SearchResult
    {
        $res = $request->all();
        $values = $this->parseAttributeFilters($res);

        $response = $this->client->search([
            'index' => 'adverts',
            'type' => 'advert',
            'body' => [
                '_source' => ['id'],
                'from' => ($page - 1) * $perPage,
                'size' => $perPage,
                'sort' => empty($request['query']) ? [
                    ['published_at' => ['order' => 'desc']],
                ] : [],
                'aggs' => [
                    'group_by_region' => [
                        'terms' => [
                            'field' => 'regions',
                        ],
                    ],
                    'group_by_category' => [
                        'terms' => [
                            'field' => 'categories',
                        ],
                    ],
                ],
                'query' => [
                    'bool' => [
                        'must' => array_merge(
                            [
                                ['term' => ['status' => Advert::STATUS_ACTIVE]],
                            ],
                            array_filter([
                                $category ? ['term' => ['categories' => $category->id]] : false,
                                $region ? ['term' => ['regions' => $region->id]] : false,
                                !empty($request['query']) ? ['multi_match' => [
                                    'query' => $request['query'],
                                    'fields' => [ 'title^3', 'content' ]
                                ]] : false,
                            ]),
                            array_map(function ($value, $id) {
                                return [
                                    'nested' => [
                                        'path' => 'values',
                                        'query' => [
                                            'bool' => [
                                                'must' => array_values(array_filter([
                                                    ['match' => ['values.attribute' => $id]],
                                                    !empty($value['equals']) ? ['match' => ['values.value_string' => $value['equals']]] : false,
                                                    !empty($value['from']) ? ['range' => ['values.value_int' => ['gte' => $value['from']]]] : false,
                                                    !empty($value['to']) ? ['range' => ['values.value_int' => ['lte' => $value['to']]]] : false,
                                                ])),
                                            ],
                                        ],
                                    ],
                                ];
                            }, $values, array_keys($values))
                        )
                    ],
                ],
            ],
        ]);

        $ids = array_column($response['hits']['hits'], '_id');

        if ($ids) {
            $items = Advert::query()->active()
                ->with(['category', 'region', 'firstPhoto', 'favorites'])
                ->whereIn('id', $ids)
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
                ->get();
            $pagination = new LengthAwarePaginator($items, $response['hits']['total']['value'], $perPage, $page);
            $pagination->withPath('/search')->appends($request->query());
        } else {
            $pagination = new LengthAwarePaginator([], 0, $perPage, $page);
            $pagination->withPath('/search')->appends($request->query());
        }

        return new SearchResult(
            $pagination,
            array_column($response['aggregations']['group_by_region']['buckets'], 'doc_count', 'key'),
            array_column($response['aggregations']['group_by_category']['buckets'], 'doc_count', 'key')
        );
    }

    private function parseAttributeFilters(array $data): array
    {
        $attrs = [];

        foreach ($data as $key => $value) {
            if (preg_match('/^(\d+)(?:_(from|to))?$/', $key, $matches)) {
                $attrId = (int)$matches[1];
                $type = $matches[2] ?? 'equals';

                if (!isset($attrs[$attrId])) {
                    $attrs[$attrId] = [];
                }

                $attrs[$attrId][$type] = $value;
            }
        }

        return array_filter($attrs, function ($value) {
            return !empty($value['equals']) || !empty($value['from']) || !empty($value['to']);
        });
    }


}
