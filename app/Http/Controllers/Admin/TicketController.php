<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * Admin Ticket Controller
 * 
 * Manages customer support tickets.
 */
class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'assignedTo'])->latest('created_at');

        // Filters
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($customer = $request->get('customer_id')) {
            $query->where('customer_id', $customer);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
        ];

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'stats' => $stats,
            'filters' => [
                'status' => $request->get('status'),
                'priority' => $request->get('priority'),
                'customer_id' => $request->get('customer_id'),
                'search' => $request->get('search'),
            ],
        ]);
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'assignedTo', 'replies.user']);

        return view('admin.tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Reply to the specified ticket.
     */
    public function reply(Ticket $ticket, Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'user_type' => 'admin',
            'message' => $validated['message'],
            'is_internal' => false,
        ]);

        // Update ticket status if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        // Update last reply timestamp
        $ticket->update(['last_reply_at' => now()]);

        // Notify customer
        $ticket->customer->notify(new \App\Notifications\Customer\TicketReplyNotification($ticket));

        return back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Mark the ticket as resolved.
     */
    public function resolve(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
        ]);

        // Notify customer
        $ticket->customer->notify(new \App\Notifications\Customer\TicketResolvedNotification($ticket));

        return back()->with('success', 'Ticket marked as resolved.');
    }

    /**
     * Close the ticket.
     */
    public function close(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Ticket closed successfully.');
    }
}
