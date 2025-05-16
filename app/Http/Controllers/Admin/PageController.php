<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageRequest;
use App\Http\Services\PageService;
use App\Models\Page;
use Inertia\Inertia;


class PageController
{
    public function __construct(private readonly PageService $pageService)
    {
//        $this->middleware('can:manage-pages');
    }

    public function index()
    {
        return Inertia::render('Admin/Page/Index', [
            'pages' => $this->pageService->getPages()
        ]);
    }



    public function create()
    {
        return response()->json([
            'pages' => $this->pageService->getPages()
        ]);
    }

    public function store(PageRequest $request)
    {
        $page = Page::create([
            'title' => $request['title'],
            'slug' => $request['slug'],
            'menu_title' => $request['menu_title'],
            'parent_id' => $request['parent_id'],
            'content' => $request['content'],
            'description' => $request['description'],
        ]);

        return redirect()->route('admin.pages.show', $page);
    }

    public function show(Page $page)
    {
        return Inertia::render('Admin/Page/Show', [
            'page' => $page
        ]);
    }

    public function edit(Page $page)
    {

        return response()->json([
            'page' => $page,
            'pages' => Page::defaultOrder()->withDepth()->get()
        ]);
    }

    public function update(PageRequest $request, Page $page)
    {
        $page->update([
            'title' => $request['title'],
            'slug' => $request['slug'],
            'menu_title' => $request['menu_title'],
            'parent_id' => $request['parent'],
            'content' => $request['content'],
            'description' => $request['description'],
        ]);

        return redirect()->route('admin.pages.show', $page);
    }

    public function first(Page $page)
    {
        if ($first = $page->siblings()->defaultOrder()->first()) {
            $page->insertBeforeNode($first);
        }

        return redirect()->route('admin.pages.index');
    }

    public function up(Page $page)
    {
        $page->up();

        return redirect()->route('admin.pages.index');
    }

    public function down(Page $page)
    {
        $page->down();

        return redirect()->route('admin.pages.index');
    }

    public function last(Page $page)
    {
        if ($last = $page->siblings()->defaultOrder('desc')->first()) {
            $page->insertAfterNode($last);
        }

        return redirect()->route('admin.pages.index');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index');
    }
}
