<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    public function generateTicketsForOrder(Order $order, array $ticketData): void
    {
        foreach ($ticketData as $item) {
            $ticketType = TicketType::find($item['ticket_type_id']);
            
            if (!$ticketType) continue;

            for ($i = 0; $i < $item['quantity']; $i++) {
                $ticket = Ticket::create([
                    'order_id' => $order->id,
                    'ticket_type_id' => $ticketType->id,
                    'attendee_name' => $order->buyer_name,
                    'attendee_email' => $order->buyer_email,
                ]);

                $this->generateQrCode($ticket);
                
                // Update ticket type quantity sold
                $ticketType->increment('quantity_sold');
            }
        }
    }

    public function generateQrCode(Ticket $ticket): void
    {
        $qrContent = route('api.checkin', ['uuid' => $ticket->uuid]);
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrContent);

        $path = 'qrcodes/' . $ticket->uuid . '.svg';
        Storage::disk('public')->put($path, $qrCode);

        $ticket->update(['qr_code_path' => $path]);
    }

    public function validateTicket(string $uuid): array
    {
        $ticket = Ticket::where('uuid', $uuid)->first();

        if (!$ticket) {
            return [
                'valid' => false,
                'status' => 404,
                'message' => 'Ticket not found',
            ];
        }

        if ($ticket->status === 'cancelled') {
            return [
                'valid' => false,
                'status' => 410,
                'message' => 'Ticket has been cancelled',
            ];
        }

        if ($ticket->is_checked_in) {
            return [
                'valid' => false,
                'status' => 409,
                'message' => 'Ticket already checked in at ' . $ticket->checked_in_at->format('H:i'),
                'ticket' => $ticket,
            ];
        }

        return [
            'valid' => true,
            'status' => 200,
            'message' => 'Valid ticket',
            'ticket' => $ticket,
        ];
    }

    public function checkIn(Ticket $ticket, string $method = 'qr', ?string $checkedInBy = null): bool
    {
        return $ticket->checkIn($method, $checkedInBy);
    }
}
