<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::select('buyer_email', 'buyer_name')
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as total_spent')
            ->selectRaw('MAX(created_at) as last_order')
            ->groupBy('buyer_email', 'buyer_name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('buyer_name', 'like', "%{$search}%")
                    ->orWhere('buyer_email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('total_spent')->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(string $email)
    {
        $orders = Order::with(['event', 'tickets'])
            ->where('buyer_email', $email)
            ->latest()
            ->get();

        $customer = [
            'email' => $email,
            'name' => $orders->first()?->buyer_name ?? 'Unknown',
            'phone' => $orders->first()?->buyer_phone,
            'total_orders' => $orders->count(),
            'total_spent' => $orders->where('payment_status', 'completed')->sum('total_amount'),
            'total_tickets' => $orders->sum(fn($o) => $o->tickets->count()),
        ];

        return view('admin.customers.show', compact('customer', 'orders'));
    }
}
