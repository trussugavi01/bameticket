<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private StripeService $stripeService
    ) {}

    public function show(Event $event)
    {
        if (!in_array($event->status, ['published', 'selling'])) {
            abort(404);
        }

        $event->load(['ticketTypes' => fn($q) => $q->available()]);

        return view('checkout.show', compact('event'));
    }

    public function process(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_type_id' => 'required|exists:ticket_types,id',
            'tickets.*.quantity' => 'required|integer|min:1|max:10',
        ]);

        $cart = collect($validated['tickets'])->filter(fn($t) => $t['quantity'] > 0)->toArray();

        if (empty($cart)) {
            return back()->with('error', 'Please select at least one ticket.');
        }

        try {
            $session = $this->stripeService->createCheckoutSession($event, $cart, [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to process checkout. Please try again.');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('home');
        }

        try {
            $session = $this->stripeService->retrieveSession($sessionId);
            
            $order = Order::where('stripe_session_id', $sessionId)->first();

            return view('checkout.success', compact('order', 'session'));
        } catch (\Exception $e) {
            return view('checkout.success', ['order' => null, 'session' => null]);
        }
    }

    public function cancel(Event $event)
    {
        return view('checkout.cancel', compact('event'));
    }
}
