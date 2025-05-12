<?php

namespace App\Console\Commands\Search;

use App\Models\Adverts\Advert;
use App\Models\Banners\Banner;
use App\Services\Search\AdvertIndexer;
use App\Services\Search\BannerIndexer;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';

    public function __construct(
        private readonly AdvertIndexer $adverts,
        private readonly BannerIndexer $banners)
    {
        parent::__construct();
    }

    public function handle(): bool
    {
        $this->adverts->clear();

        foreach (Advert::query()->active()->orderBy('id')->cursor() as $advert) {
            $this->adverts->index($advert);
        }

        $this->banners->clear();

        foreach (Banner::active()->orderBy('id')->cursor() as $banner) {
            $this->banners->index($banner);
        }

        return true;
    }
}
