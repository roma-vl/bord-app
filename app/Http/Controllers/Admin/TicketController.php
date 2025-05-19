<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\EditRequest;
use App\Http\Requests\Ticket\MessageRequest;
use App\Http\Services\TicketService;
use App\Models\Ticket\Status;
use App\Models\Ticket\Ticket;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function __construct(private readonly TicketService $ticketService) {}

    public function index(Request $request): Response
    {
        $query = Ticket::orderByDesc('updated_at')
            ->with('user');

        if (! empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (! empty($value = $request->get('user'))) {
            $query->where('user_id', $value);
        }

        if (! empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        $tickets = $query->paginate(20);
        $statuses = Status::statusesList();

        return Inertia::render('Admin/Ticket/Index', [
            'tickets' => $tickets,
            'statuses' => $statuses,
        ]);

    }

    public function show(Ticket $ticket): Response
    {
        return Inertia::render('Admin/Ticket/Show', [
            'ticket' => $ticket,
            'statuses' => $ticket->statuses()->get(),
            'messages' => $ticket->messages()->with('user')->get(),
        ]);
    }

    public function edit(EditRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->edit($ticket->id, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function message(MessageRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->message(Auth::id(), $ticket->id, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.tickets.show', $ticket);
    }

    public function approve(Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->approve(Auth::id(), $ticket->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function close(Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->close(Auth::id(), $ticket->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function reopen(Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->reopen(Auth::id(), $ticket->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->removeByAdmin($ticket->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.index');
    }
}
