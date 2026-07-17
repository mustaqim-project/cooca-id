<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = $request->user()->tickets()
            ->latest()
            ->paginate(15);

        return view('customer.tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('customer.tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $ticket = $request->user()->tickets()->create([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'open',
        ]);

        event(new \App\Events\Ticket\TicketCreated($ticket));

        return redirect()->route('customer.tickets.index')->with('status', 'Ticket created successfully.');
    }

    public function show(Request $request, Ticket $ticket): View
    {
        if ($request->user()->id !== $ticket->customer_id) {
            abort(403);
        }

        $ticket->load('replies.user');

        return view('customer.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket): RedirectResponse
    {
        if ($request->user()->id !== $ticket->customer_id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'user_type' => 'customer',
            'message' => $validated['message'],
        ]);

        $ticket->update(['status' => 'open']);

        return redirect()->route('customer.tickets.show', $ticket)->with('status', 'Reply added successfully.');
    }
}
