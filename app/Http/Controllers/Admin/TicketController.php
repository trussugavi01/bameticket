<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['order.event', 'ticketType']);

        if ($request->filled('event')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('event_id', $request->event);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('checked_in')) {
            $query->where('is_checked_in', $request->checked_in === 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('attendee_name', 'like', "%{$search}%")
                    ->orWhere('attendee_email', 'like', "%{$search}%")
                    ->orWhere('uuid', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(20);
        $events = Event::orderBy('title')->get();

        return view('admin.tickets.index', compact('tickets', 'events'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['order.event', 'ticketType', 'tableAssignment.table']);
        
        return view('admin.tickets.show', compact('ticket'));
    }

    public function downloadPdf(Ticket $ticket)
    {
        // TODO: Implement PDF ticket download
        
        return back()->with('info', 'PDF download coming soon.');
    }
}
