<?php

namespace App\Http\Controllers\Cabinet\Chat;

use App\Http\Controllers\Controller;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Dialog\Dialog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $dialogs = Dialog::where('user_id', $user->id)
            ->orWhere('client_id', $user->id)
            ->with('messages.user', 'client')
            ->get();

        return Inertia::render('Account/Chat/Index', [
            'dialogs' => $dialogs,
        ]);
    }

    public function getDialogByAdvert(Request $request, Advert $advert): JsonResponse
    {
        $user = $request->user();

        $dialog = $advert->dialogs()->where('user_id', $user->id)
            ->orWhere('client_id', $user->id)
            ->with('messages.user', 'client')
            ->get()->first();

        return response()->json($dialog);
    }

    public function show(Dialog $chat)
    {
        $messages = $chat->messages()->with('user')->get();

        return response()->json($messages);
    }

    public function store(Request $request, Advert $advert)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if ($advert->user->id !== $request->user()->id) {
            $advert->writeClientMessage($request->user()->id, $request->input('message'));
        } else {
            $advert->writeOwnerMessage($request->input('client_id'), $request->input('message'));
        }
    }

    public function createDialog(Request $request, Advert $advert)
    {
        $ff = 'ads';
        $request->validate([
            'message' => 'required|string',
        ]);
    }
}
