<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['event', 'tickets.ticketType']);

        if ($request->filled('event')) {
            $query->where('event_id', $request->event);
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('buyer_name', 'like', "%{$search}%")
                    ->orWhere('buyer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(15);
        $events = Event::orderBy('title')->get();

        return view('admin.orders.index', compact('orders', 'events'));
    }

    public function show(Order $order)
    {
        $order->load(['event', 'tickets.ticketType', 'refunds.processedBy']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function resendConfirmation(Order $order)
    {
        // TODO: Implement email resend logic
        
        return back()->with('success', 'Confirmation email resent.');
    }

    public function downloadInvoice(Order $order)
    {
        // TODO: Implement invoice PDF generation
        
        return back()->with('info', 'Invoice download coming soon.');
    }
}
