<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = Refund::with(['order.event', 'processedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $refunds = $query->latest()->paginate(15);

        // Stats
        $refundedThisMonth = Refund::completed()
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        $totalOrders = Order::paid()->whereMonth('created_at', now()->month)->count();
        $refundedOrders = Order::whereMonth('created_at', now()->month)
            ->where('refund_status', '!=', 'none')->count();
        $refundRate = $totalOrders > 0 ? round(($refundedOrders / $totalOrders) * 100, 1) : 0;
        
        $openClaims = Refund::where('status', 'pending')->count();
        
        $avgProcessTime = Refund::completed()
            ->whereMonth('created_at', now()->month)
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, processed_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

        return view('admin.refunds.index', compact(
            'refunds', 'refundedThisMonth', 'refundRate', 'openClaims', 'avgProcessTime'
        ));
    }

    public function create(Order $order)
    {
        if (!$order->can_refund) {
            return back()->with('error', 'This order cannot be refunded.');
        }

        return view('admin.refunds.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'type' => 'required|in:full,partial',
            'amount' => 'required_if:type,partial|nullable|numeric|min:0.01|max:' . $order->refundable_amount,
            'reason' => 'required|string|max:1000',
        ]);

        if (!$order->can_refund) {
            return back()->with('error', 'This order cannot be refunded.');
        }

        $amount = $validated['type'] === 'full' ? $order->refundable_amount : $validated['amount'];

        DB::transaction(function () use ($order, $validated, $amount) {
            $refund = Refund::create([
                'order_id' => $order->id,
                'processed_by' => auth()->id(),
                'amount' => $amount,
                'type' => $validated['type'],
                'status' => 'completed', // In real app, would be 'pending' until Stripe confirms
                'reason' => $validated['reason'],
                'processed_at' => now(),
            ]);

            // Update order refund status
            $order->update([
                'refunded_amount' => $order->refunded_amount + $amount,
                'refund_status' => ($order->refunded_amount + $amount >= $order->total_amount) ? 'full' : 'partial',
            ]);

            // Audit log
            AuditLog::log(
                'refund.processed',
                $refund,
                ['payment_status' => 'completed', 'refund_status' => 'none'],
                ['payment_status' => 'completed', 'refund_status' => $order->refund_status, 'refund_amount' => $amount],
                'high'
            );
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Refund processed successfully.');
    }

    public function export(Request $request)
    {
        // TODO: Implement CSV export
        
        return back()->with('info', 'Export coming soon.');
    }
}
