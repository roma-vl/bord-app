<?php

namespace App\Http\Repositories;

class BaseRepository
{
    const int PER_PAGE = 5;
    const string SORT_BY_DEFAULT = 'id';
    const string SORT_ORDER_DEFAULT = 'asc';
}
