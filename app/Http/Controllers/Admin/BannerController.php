<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Cabinet\Banners\EditRequest;
use App\Http\Requests\Cabinet\Banners\RejectRequest;
use App\Http\Services\Banner\BannerService;
use App\Models\Banners\Banner;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    public function __construct(
        private readonly BannerService $bannerService
    ) {}

    public function index(Request $request): Response
    {
        $query = Banner::query()->orderByDesc('updated_at');

        if (! empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (! empty($value = $request->get('user'))) {
            $query->where('user', $value);
        }

        if (! empty($value = $request->get('region'))) {
            $query->where('region', $value);
        }

        if (! empty($value = $request->get('category'))) {
            $query->where('category', $value);
        }

        if (! empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        $banners = $query->paginate(self::PER_PAGE);

        $statuses = Banner::statusesList();

        return Inertia::render('Admin/Banner/Index', [
            'banners' => $banners,
            'statuses' => $statuses,
        ]);

    }

    public function show(Banner $banner): Response
    {
        return Inertia::render('Account/Banner/Show', [
            'banner' => $banner,
        ]);
    }

    public function editForm(Banner $banner): Response
    {
        return Inertia::render('Account/Banner/Edit', [
            'banner' => $banner,
        ]);
    }

    public function edit(EditRequest $request, Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->editByAdmin($banner->id, $request);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.banner.show', $banner);
    }

    public function moderate(Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->moderate($banner->id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.banner.show', $banner);
    }

    public function rejectForm(Banner $banner): Response
    {
        return Inertia::render('Account/Banner/Reject', [
            'banner' => $banner,
        ]);
    }

    public function reject(RejectRequest $request, Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->reject($banner->id, $request);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.banner.show', $banner);
    }

    public function pay(Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->pay($banner->id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.banner.show', $banner);
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->removeByAdmin($banner->id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.banner.index');
    }
}
