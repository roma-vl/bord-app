<?php

namespace App\Http\Controllers;

use App\Http\Services\PageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController
{
    public function __construct(private readonly PageService  $pageService){}

    public function show(Request $request, ?string $urlPath = '')
    {
//        if (1 <= count($page = $this->pageService->parsePageFromUrl($urlPath))) {
            $page = $this->pageService->parsePageFromUrl($urlPath);
            return Inertia::render('Page/Show', [
                'page' => $page->first()
            ]);
//        }

    }
}
