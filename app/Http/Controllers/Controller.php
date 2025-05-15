<?php

namespace App\Http\Controllers;

abstract class Controller
{
    const int PER_PAGE = 5;
    const string SORT_BY_DEFAULT = 'id';
    const string SORT_ORDER_DEFAULT = 'asc';
    const array DEFAULT_ROLE_TO_USER = [3];
}
