<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\WebhookLog;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    public function handleStripe(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Log the webhook
        $log = WebhookLog::create([
            'event_id' => $event->id,
            'event_type' => $event->type,
            'payload' => $event->toArray(),
            'status' => 'processing',
        ]);

        $startTime = microtime(true);

        try {
            $this->processWebhookEvent($event);
            
            $log->markAsSuccess((int) ((microtime(true) - $startTime) * 1000));
            
            return response('Webhook handled', 200);
        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());
            
            return response('Webhook processing failed', 500);
        }
    }

    private function processWebhookEvent($event): void
    {
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            case 'charge.refunded':
                $this->handleChargeRefunded($event->data->object);
                break;
        }
    }

    private function handleCheckoutSessionCompleted($session): void
    {
        $metadata = $session->metadata;
        
        $eventModel = Event::find($metadata->event_id);
        if (!$eventModel) return;

        // Create order
        $order = Order::create([
            'event_id' => $eventModel->id,
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $session->payment_intent,
            'stripe_customer_id' => $session->customer,
            'buyer_name' => $metadata->buyer_name,
            'buyer_email' => $metadata->buyer_email,
            'buyer_phone' => $metadata->buyer_phone,
            'subtotal' => $session->amount_subtotal / 100,
            'vat_amount' => ($session->amount_total - $session->amount_subtotal) / 100,
            'total_amount' => $session->amount_total / 100,
            'currency' => strtoupper($session->currency),
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        // Generate tickets
        $ticketData = json_decode($metadata->ticket_data, true);
        $this->ticketService->generateTicketsForOrder($order, $ticketData);

        // TODO: Send confirmation email
    }

    private function handlePaymentIntentSucceeded($paymentIntent): void
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order && $order->payment_status !== 'completed') {
            $order->update([
                'payment_status' => 'completed',
                'paid_at' => now(),
            ]);
        }
    }

    private function handlePaymentIntentFailed($paymentIntent): void
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->update(['payment_status' => 'failed']);
        }
    }

    private function handleChargeRefunded($charge): void
    {
        $order = Order::where('stripe_payment_intent_id', $charge->payment_intent)->first();
        
        if ($order) {
            $refundedAmount = $charge->amount_refunded / 100;
            
            $order->update([
                'refunded_amount' => $refundedAmount,
                'refund_status' => $refundedAmount >= $order->total_amount ? 'full' : 'partial',
            ]);
        }
    }
}
