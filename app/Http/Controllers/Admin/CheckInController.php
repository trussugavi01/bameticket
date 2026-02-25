<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    public function index(Request $request)
    {
        $events = Event::whereIn('status', ['selling', 'published'])
            ->where('start_date', '>=', now()->subDay())
            ->orderBy('start_date')
            ->get();

        $selectedEvent = null;
        $stats = null;

        if ($request->filled('event')) {
            $selectedEvent = Event::find($request->event);
            
            if ($selectedEvent) {
                $stats = [
                    'total_tickets' => Ticket::whereHas('order', fn($q) => $q->where('event_id', $selectedEvent->id))->count(),
                    'checked_in' => Ticket::whereHas('order', fn($q) => $q->where('event_id', $selectedEvent->id))->where('is_checked_in', true)->count(),
                    'pending' => Ticket::whereHas('order', fn($q) => $q->where('event_id', $selectedEvent->id))->where('is_checked_in', false)->count(),
                ];
                $stats['percentage'] = $stats['total_tickets'] > 0 ? round(($stats['checked_in'] / $stats['total_tickets']) * 100, 1) : 0;
            }
        }

        return view('admin.checkin.index', compact('events', 'selectedEvent', 'stats'));
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $uuid = $validated['code'];
        
        // Try to extract UUID from URL if scanned from QR
        if (str_contains($uuid, '/')) {
            $parts = explode('/', $uuid);
            $uuid = end($parts);
        }

        $result = $this->ticketService->validateTicket($uuid);

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'status' => $result['status'],
            ], 200); // Return 200 so JS can handle it
        }

        $ticket = $result['ticket'];
        $this->ticketService->checkIn($ticket, 'admin_scan', auth()->user()->name);

        $ticket->load(['order.event', 'ticketType']);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'ticket' => [
                'number' => $ticket->ticket_number,
                'attendee' => $ticket->attendee_name ?? $ticket->order->buyer_name,
                'type' => $ticket->ticketType->name,
                'checked_in_at' => $ticket->checked_in_at->format('H:i:s'),
            ],
        ]);
    }

    public function recentCheckins(Request $request)
    {
        $query = Ticket::with(['order.event', 'ticketType'])
            ->where('is_checked_in', true)
            ->orderBy('checked_in_at', 'desc');

        if ($request->filled('event')) {
            $query->whereHas('order', fn($q) => $q->where('event_id', $request->event));
        }

        $checkins = $query->take(20)->get();

        return response()->json([
            'checkins' => $checkins->map(fn($t) => [
                'ticket_number' => $t->ticket_number,
                'attendee' => $t->attendee_name ?? $t->order->buyer_name,
                'type' => $t->ticketType->name,
                'checked_in_at' => $t->checked_in_at->format('H:i:s'),
            ]),
        ]);
    }
}
