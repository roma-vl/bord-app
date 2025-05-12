<?php

namespace App\Http\Controllers\Cabinet\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Banners\EditRequest;
use App\Http\Requests\Cabinet\Banners\FileRequest;
use App\Http\Services\Banner\BannerService;
use App\Models\Banners\Banner;
use Auth;
use DomainException;
use Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    public function __construct(
        private readonly BannerService $bannerService,
    )
    {}

    public function index(): Response
    {
        $banners = Banner::forUser(Auth::user())->orderBy('id')->paginate(self::PER_PAGE);

        return Inertia::render('Account/Banner/Index', [
            'banners' => $banners
        ]);
    }

    public function show(Banner $banner): Response
    {
//        $this->checkAccess($banner);
        return Inertia::render('Account/Banner/Show', [
            'banner' => $banner
        ]);
    }

    public function edit(Banner $banner): Response|RedirectResponse
    {
//        $this->checkAccess($banner);
        if ($banner->canBeChanged()) {
            return back()->with('error', 'Enable to edit this banner!');
        }
        return Inertia::render('Account/Banner/Edit', [
            'banner' => $banner
        ]);
    }

    public function update(EditRequest $request, Banner $banner): RedirectResponse
    {
//        $this->checkAccess($banner);
        try {
            $this->bannerService->editByOwner($banner->id, $request);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('accounts.banner.index')->with('success', 'Banner updated!>!');
    }

    public function fileForm(Banner $banner): View|Application|Factory|RedirectResponse
    {
        $this->checkAccess($banner);
        if (!$banner->canBeChanged()) {
            return redirect()->route('account.banners.show', $banner)->with('error', 'Unable to edit.');
        }
        $formats = Banner::formatsList();
        return view('cabinet.banners.file', compact('banner', 'formats'));
    }

    public function file(FileRequest $request, Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->bannerService->changeFile($banner->id, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.banners.show', $banner);
    }

    public function send(Banner $banner): RedirectResponse
    {
//        $this->checkAccess($banner);
        try {
            $this->bannerService->sendToModeration($banner->id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('account.banners.show', $banner);
    }

    public function cancel(Banner $banner): RedirectResponse
    {
//        $this->checkAccess($banner);
        try {
            $this->bannerService->cancelModeration($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.banners.show', $banner);
    }

    public function order(Banner $banner): Application|Redirector|RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            //@TODO зробити оплату через банк або рахунок на ручну оплату
            if ( $banner = $this->bannerService->order($banner->id)) {
                $url = 'https::/посилання на оплату' . $banner;
                return redirect($url);
            }
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.banners.show', $banner);
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->bannerService->removeByOwner($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.banners.index');
    }

    private function checkAccess(Banner $banner): void
    {
        if (!Gate::allows('manage-own-banner', $banner)) {
            abort(403);
        }
    }
}
