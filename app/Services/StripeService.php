<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use App\Models\TicketType;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Refund;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Event $event, array $cart, array $buyer): Session
    {
        $lineItems = [];
        $metadata = [
            'event_id' => $event->id,
            'buyer_name' => $buyer['name'],
            'buyer_email' => $buyer['email'],
            'buyer_phone' => $buyer['phone'] ?? null,
        ];

        $ticketData = [];

        foreach ($cart as $item) {
            $ticketType = TicketType::findOrFail($item['ticket_type_id']);
            
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'gbp',
                    'product_data' => [
                        'name' => $event->title . ' - ' . $ticketType->name,
                        'description' => $ticketType->description ?? 'Ticket for ' . $event->title,
                    ],
                    'unit_amount' => (int) ($ticketType->current_price * 100),
                ],
                'quantity' => $item['quantity'],
            ];

            $ticketData[] = [
                'ticket_type_id' => $ticketType->id,
                'quantity' => $item['quantity'],
                'unit_price' => $ticketType->current_price,
            ];
        }

        $metadata['ticket_data'] = json_encode($ticketData);

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', ['event' => $event->slug]),
            'customer_email' => $buyer['email'],
            'metadata' => $metadata,
            'payment_intent_data' => [
                'metadata' => $metadata,
            ],
        ]);
    }

    public function retrieveSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }

    public function createRefund(Order $order, float $amount): \Stripe\Refund
    {
        return Refund::create([
            'payment_intent' => $order->stripe_payment_intent_id,
            'amount' => (int) ($amount * 100),
        ]);
    }
}
