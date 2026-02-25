<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfTicketService
{
    public function generateTicketPdf(Ticket $ticket): string
    {
        $ticket->load(['order.event', 'ticketType']);

        $pdf = Pdf::loadView('pdf.ticket', [
            'ticket' => $ticket,
            'event' => $ticket->order->event,
            'order' => $ticket->order,
        ]);

        $path = 'tickets/' . $ticket->uuid . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    public function generateOrderTicketsPdf(Order $order): string
    {
        $order->load(['event', 'tickets.ticketType']);

        $pdf = Pdf::loadView('pdf.order-tickets', [
            'order' => $order,
            'event' => $order->event,
            'tickets' => $order->tickets,
        ]);

        $path = 'orders/' . $order->order_number . '-tickets.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    public function streamTicketPdf(Ticket $ticket)
    {
        $ticket->load(['order.event', 'ticketType']);

        return Pdf::loadView('pdf.ticket', [
            'ticket' => $ticket,
            'event' => $ticket->order->event,
            'order' => $ticket->order,
        ])->stream('ticket-' . $ticket->ticket_number . '.pdf');
    }
}
