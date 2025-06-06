<?php

namespace App\Http\Controllers\Cabinet\Profile;

use App\Contracts\SmsServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\PhoneUpdateRequest;
use Carbon\Carbon;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PhoneController extends Controller
{
    private SmsServiceInterface $smsService;

    public function __construct(SmsServiceInterface $smsService)
    {
        $this->smsService = $smsService;
    }

    public function update(PhoneUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $oldPhone = $user->phone;
        $request->user()->fill($request->validated());

        $request->user()->save();

        if ($oldPhone != $user->phone) {
            $user->unverifyPhone();
        }

        //        Mail::to($request->user()->email)->send(new \App\Mail\TestEmail($request->user()));
        return Redirect::route('account.profile.settings')->with('success', __('profile.phone_update'));
    }

    public function request(Request $request): RedirectResponse
    {
        $user = Auth::user();

        try {
            $token = $user->requestPhoneVerification(Carbon::now());
            $this->smsService->sendVerifyCode(phone: $user->phone, code: $token);
        } catch (DomainException $e) {
            request()->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('account.profile.phone.form');
    }

    public function form(Request $request): Response
    {
        return Inertia::render('Account/Profile/VerifyCodeForm');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        try {
            $user->verifyPhone($request['token'], Carbon::now());
        } catch (DomainException $e) {
            return redirect()->route('account.profile.phone.form')->with('error', $e->getMessage());
        }

        return redirect()->route('account.profile.index');
    }
}
