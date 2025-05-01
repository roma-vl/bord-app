<?php

namespace App\Repositories;

use App\Models\Adverts\Advert;
use App\Parents\Repositories\ElasticsearchRepository;

class AdvertRepository extends ElasticsearchRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return Advert::class;
    }
}
