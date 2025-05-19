<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\CreateRequest;
use App\Http\Requests\Ticket\MessageRequest;
use App\Http\Services\TicketService;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function __construct(private readonly TicketService $ticketService){}

    public function index()
    {
        $tickets = Ticket::forUser(Auth::user())
            ->orderByDesc('updated_at')
            ->paginate(20);

        return Inertia::render('Account/Ticket/Index', [
            'tickets' => $tickets
        ]);
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('Account/Ticket/Show', [
            'ticket'   => $ticket,
            'statuses' => $ticket->statuses()->get(),
            'messages' => $ticket->messages()->with('user')->get()
        ]);
    }

    public function store(CreateRequest $request)
    {
        try {
            $ticket = $this->ticketService->create(Auth::id(), $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.tickets.show', $ticket);
    }

    public function message(MessageRequest $request, Ticket $ticket)
    {
        try {
            $this->ticketService->message(Auth::id(), $ticket->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $this->checkAccess($ticket);
        try {
            $this->ticketService->removeByOwner($ticket->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.favorites.index');
    }

    private function checkAccess(Ticket $ticket): void
    {
        if (!Gate::allows('manage-own-ticket', $ticket)) {
            abort(403);
        }
    }
}
