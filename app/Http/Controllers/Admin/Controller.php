<?php

namespace App\Http\Controllers\Admin;

abstract class Controller
{
    const int PER_PAGE = 5;
    const string SORT_BY_DEFAULT = 'id';
    const string SORT_ORDER_DEFAULT = 'asc';
}
