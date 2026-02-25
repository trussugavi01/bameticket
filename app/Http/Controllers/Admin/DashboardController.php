<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Refund;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Revenue metrics
        $totalRevenue = Order::paid()->sum('total_amount');
        $revenueChange = $this->calculateRevenueChange();
        
        // Ticket metrics
        $ticketsSold = Ticket::count();
        $ticketsChange = $this->calculateTicketsChange();
        
        // Active events
        $activeEvents = Event::whereIn('status', ['published', 'selling'])->count();
        
        // Recent orders
        $recentOrders = Order::with(['event', 'tickets'])
            ->latest()
            ->take(5)
            ->get();
        
        // Sales by ticket category
        $salesByCategory = DB::table('tickets')
            ->join('ticket_types', 'tickets.ticket_type_id', '=', 'ticket_types.id')
            ->join('orders', 'tickets.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'completed')
            ->select('ticket_types.name', DB::raw('COUNT(*) as count'), DB::raw('SUM(ticket_types.price) as revenue'))
            ->groupBy('ticket_types.id', 'ticket_types.name')
            ->orderByDesc('count')
            ->take(5)
            ->get();
        
        // Upcoming event
        $upcomingEvent = Event::where('start_date', '>', now())
            ->whereIn('status', ['published', 'selling'])
            ->orderBy('start_date')
            ->first();
        
        // Active event managers
        $activeManagers = \App\Models\User::role(['Event Admin', 'Super Admin'])
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'revenueChange',
            'ticketsSold',
            'ticketsChange',
            'activeEvents',
            'recentOrders',
            'salesByCategory',
            'upcomingEvent',
            'activeManagers'
        ));
    }

    private function calculateRevenueChange(): float
    {
        $thisMonth = Order::paid()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
            
        $lastMonth = Order::paid()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');
            
        if ($lastMonth == 0) return 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    private function calculateTicketsChange(): float
    {
        $thisMonth = Ticket::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $lastMonth = Ticket::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        if ($lastMonth == 0) return 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }
}
