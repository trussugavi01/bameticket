<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $ticket->ticket_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .ticket { border: 2px solid #0d9488; border-radius: 10px; padding: 30px; max-width: 600px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #0d9488; }
        .ticket-number { font-size: 12px; color: #6b7280; }
        .event-title { font-size: 22px; font-weight: bold; color: #111827; margin-bottom: 10px; }
        .event-details { color: #6b7280; font-size: 14px; margin-bottom: 20px; }
        .event-details p { margin: 5px 0; }
        .attendee { background: #f3f4f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .attendee-label { font-size: 12px; color: #6b7280; text-transform: uppercase; }
        .attendee-name { font-size: 18px; font-weight: bold; color: #111827; margin-top: 5px; }
        .ticket-type { display: inline-block; background: #0d9488; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        .qr-section { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px dashed #d1d5db; }
        .qr-code { width: 150px; height: 150px; margin: 0 auto; }
        .qr-instruction { font-size: 12px; color: #6b7280; margin-top: 10px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <div class="logo">NBHCA</div>
            <div class="ticket-number">{{ $ticket->ticket_number }}</div>
        </div>

        <div class="event-title">{{ $event->title }}</div>
        
        <div class="event-details">
            <p>📅 {{ $event->start_date->format('l, F d, Y') }} at {{ $event->start_date->format('H:i') }}</p>
            @if($event->venue_name)
                <p>📍 {{ $event->venue_name }}</p>
            @endif
            @if($event->dress_code)
                <p>👔 Dress Code: {{ $event->dress_code }}</p>
            @endif
        </div>

        <div class="attendee">
            <div class="attendee-label">Attendee</div>
            <div class="attendee-name">{{ $ticket->attendee_name ?? $order->buyer_name }}</div>
        </div>

        <span class="ticket-type">{{ $ticket->ticketType->name ?? 'Standard' }}</span>

        <div class="qr-section">
            @if($ticket->qr_code_path)
                <img src="{{ storage_path('app/public/' . $ticket->qr_code_path) }}" class="qr-code" alt="QR Code">
            @else
                <div style="width: 150px; height: 150px; background: #f3f4f6; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #9ca3af;">QR Code</span>
                </div>
            @endif
            <p class="qr-instruction">Present this QR code at the venue for entry</p>
        </div>

        <div class="footer">
            <p>Order: {{ $order->order_number }} | This ticket is non-transferable</p>
            <p>National B.A.M.E Health & Care Awards | nbhca.org.uk</p>
        </div>
    </div>
</body>
</html>
