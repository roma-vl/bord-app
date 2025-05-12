<?php

namespace App\Http\Services\Banner;

use App\Http\Requests\Cabinet\Banners\CreateRequest;
use App\Http\Requests\Cabinet\Banners\EditRequest;
use App\Http\Requests\Cabinet\Banners\FileRequest;
use App\Http\Requests\Cabinet\Banners\RejectRequest;
use App\Models\Adverts\Category;
use App\Models\Banners\Banner;
use App\Models\Location;
use App\Models\User;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Storage;

class BannerService
{
    public function __construct(
        private readonly CostCalculatorService $costCalculatorService,
        private readonly Client $client
    ){}

    public function create(User $user, CreateRequest $request): Banner
    {
        $categoryId = $request->input('category_id');
        $regionId = $request->input('region_id');

        $category = Category::findOrFail($categoryId);
        $region = $regionId ? Location::findOrFail($regionId) : null;

        return DB::transaction(function () use ($user, $category, $region,$request) {
            /** @var Banner $banner */
            $banner = Banner::make([
                'name' => $request['name'],
                'limit' => $request['limit'],
                'url' => $request['url'],
                'format' => $request['format'],
                'file' => $request->file('file')->store('banners', 'public'),
                'status' => Banner::STATUS_DRAFT,
            ]);

            $banner->user()->associate($user);
            $banner->category()->associate($category);
            $banner->region()->associate($region);

            $banner->saveOrFail();

            return $banner;
        });
    }
    public function changeFile($id, FileRequest $request): void
    {
        $banner = $this->getBanner($id);
        if (!$banner->canBeChanged()) {
            throw new \DomainException('Unable to edit the banner.');
        }
        Storage::delete('public/' . $banner->file);
        $banner->update([
            'format' => $request['format'],
            'file' => $request->file('file')->store('banners', 'public'),
        ]);
    }

    public function editByOwner($id, EditRequest $request): void
    {
        $banner = $this->getBanner($id);
        if (!$banner->canBeChanged()) {
            throw new \DomainException('Unable to edit the banner.');
        }
        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    public function editByAdmin($id, EditRequest $request): void
    {
        $banner = $this->getBanner($id);
        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    public function sendToModeration($id): void
    {
        $banner = $this->getBanner($id);
        $banner->sendToModeration();
    }

    public function cancelModeration($id): void
    {
        $banner = $this->getBanner($id);
        $banner->cancelModeration();
    }

    public function moderate($id): void
    {
        $banner = $this->getBanner($id);
        $banner->moderate();
    }

    public function reject($id, RejectRequest $request): void
    {
        $banner = $this->getBanner($id);
        $banner->reject($request['reject_reason']);
    }

    public function order($id): Banner
    {
        $banner = $this->getBanner($id);
        $cost = $this->costCalculatorService->calc($banner->limit);
        $banner->order($cost);
        return $banner;
    }

    public function pay($id): void
    {
        $banner = $this->getBanner($id);
        $banner->pay(Carbon::now());
    }

    public function click(Banner $banner): void
    {
        $banner->click();
    }

    private function getBanner($id): Banner
    {
        return Banner::findOrFail($id);
    }

    public function removeByOwner($id): void
    {
        $banner = $this->getBanner($id);
        if (!$banner->canBeRemoved()) {
            throw new \DomainException('Unable to remove the banner.');
        }
        $banner->delete();
        Storage::disk('public')->delete($banner->file);
    }

    public function removeByAdmin($id): void
    {
        $banner = $this->getBanner($id);
        $banner->delete();
        Storage::disk('public')->delete($banner->file);
    }

    public function getRandomForView(?int $categoryId, ?int $regionId, $format): ?Banner
    {
        $response = $this->client->search([
            'index' => 'banners',
            'type' => 'banner',
            'body' => [
                '_source' => ['id'],
                'size' => 5,
                'sort' => [
                    '_script' => [
                        'type' => 'number',
                        'script' => 'Math.random() * 200000',
                        'order' => 'asc',
                    ],
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['status' => Banner::STATUS_ACTIVE]],
                            ['term' => ['format' => $format ?: '']],
                            ['terms' => ['categories' => array_filter([$categoryId, 0])]],
                            ['term' => ['regions' => $regionId ?: 0]],
                        ],
                    ],
                ],
            ],
        ]);

        if (!$ids = array_column($response['hits']['hits'], '_id')) {
            return null;
        }

        $banner = Banner::active()
            ->with(['category', 'region'])
            ->whereIn('id', $ids)
            ->orderByRaw('FIELD(id,' . implode(',', $ids) . ')')
            ->first();

        if (!$banner) {
            return null;
        }

        $banner->view();
        return $banner;
    }
}
