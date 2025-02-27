<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Models\Adverts\Advert;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertsController extends Controller
{
    public function moderation()
    {
        $adverts_moderation = Advert::where('status', Advert::STATUS_MODERATION)->orderByDesc('created_at')->paginate(self::PER_PAGE);
        return Inertia::render('Admin/Advert/Actions/Moderation', [
            'adverts_moderation' => $adverts_moderation
        ]);
    }

    public function active(Advert $advert)
    {
        if (!$advert->isActive()) {
            $advert->active();
        }

        return back()->with('success', 'Advert is publish successfully!');
    }

    public function reject(Advert $advert, Request $request)
    {
        $validated =  $request->validate([
            'reject_reason' => 'required|string|min:3|max:255',
        ]);

        $advert->reject($validated['reject_reason']);

        return back()->with('success', 'Advert is Rejected successfully!');
    }
}
