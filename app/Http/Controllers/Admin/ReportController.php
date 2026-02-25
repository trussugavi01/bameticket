<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $period = $request->get('period', '12_months');
        
        // Calculate date range
        $endDate = now();
        $startDate = match($period) {
            '7_days' => now()->subDays(7),
            '30_days' => now()->subDays(30),
            default => now()->subMonths(12),
        };

        // Total metrics
        $totalRevenue = Order::paid()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
            
        $totalOrders = Order::paid()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        $ticketsSold = Ticket::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $refundedAmount = Refund::completed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        $refundRate = $totalRevenue > 0 ? ($refundedAmount / $totalRevenue) * 100 : 0;

        // Revenue over time
        $revenueOverTime = Order::paid()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as gross, SUM(total_amount - refunded_amount) as net')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Ticket distribution
        $ticketDistribution = DB::table('tickets')
            ->join('ticket_types', 'tickets.ticket_type_id', '=', 'ticket_types.id')
            ->whereBetween('tickets.created_at', [$startDate, $endDate])
            ->select('ticket_types.name', DB::raw('COUNT(*) as count'))
            ->groupBy('ticket_types.id', 'ticket_types.name')
            ->orderByDesc('count')
            ->get();

        // Top performing events
        $topEvents = Event::withSum(['orders' => fn($q) => $q->paid()], 'total_amount')
            ->withCount(['tickets'])
            ->orderByDesc('orders_sum_total_amount')
            ->take(5)
            ->get();

        return view('admin.reports.revenue', compact(
            'period', 'totalRevenue', 'avgOrderValue', 'ticketsSold', 'refundRate',
            'revenueOverTime', 'ticketDistribution', 'topEvents'
        ));
    }

    public function export(Request $request)
    {
        // TODO: Implement report export
        return back()->with('info', 'Export coming soon.');
    }
}
