<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    public function checkIn(Request $request, string $uuid)
    {
        $result = $this->ticketService->validateTicket($uuid);

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'ticket' => $result['ticket'] ?? null,
            ], $result['status']);
        }

        $ticket = $result['ticket'];
        $checkedInBy = $request->user()?->name ?? 'API';
        
        $this->ticketService->checkIn($ticket, 'qr', $checkedInBy);

        $ticket->load(['order.event', 'ticketType']);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'ticket' => [
                'number' => $ticket->ticket_number,
                'attendee_name' => $ticket->attendee_name ?? $ticket->order->buyer_name,
                'ticket_type' => $ticket->ticketType->name,
                'event' => $ticket->order->event->title,
                'checked_in_at' => $ticket->checked_in_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function validate(string $uuid)
    {
        $result = $this->ticketService->validateTicket($uuid);

        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message'],
            ], $result['status']);
        }

        $ticket = $result['ticket'];
        $ticket->load(['order.event', 'ticketType']);

        return response()->json([
            'valid' => true,
            'ticket' => [
                'number' => $ticket->ticket_number,
                'attendee_name' => $ticket->attendee_name ?? $ticket->order->buyer_name,
                'ticket_type' => $ticket->ticketType->name,
                'event' => $ticket->order->event->title,
                'is_checked_in' => $ticket->is_checked_in,
            ],
        ]);
    }
}
