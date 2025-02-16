<?php

namespace App\Http\Controllers\Cabinet\Chat;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function index(): Response    {
        return Inertia::render('Account/Chat/Index');
    }

}
