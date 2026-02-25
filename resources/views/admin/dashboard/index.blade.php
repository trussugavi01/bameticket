@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Revenue Engine Overview</h1>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Total Revenue -->
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Revenue</dt>
            <dd class="mt-1 flex items-baseline justify-between">
                <span class="text-3xl font-semibold tracking-tight text-gray-900">£{{ number_format($totalRevenue, 2) }}</span>
                @if($revenueChange != 0)
                    <span class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium {{ $revenueChange > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $revenueChange > 0 ? '↑' : '↓' }} {{ abs($revenueChange) }}%
                    </span>
                @endif
            </dd>
            <div class="mt-4 flex space-x-1">
                @for($i = 0; $i < 6; $i++)
                    <div class="h-8 w-6 rounded bg-teal-{{ 200 + ($i * 100) }}"></div>
                @endfor
            </div>
        </div>

        <!-- Tickets Sold -->
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Tickets Sold</dt>
            <dd class="mt-1 flex items-baseline justify-between">
                <span class="text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($ticketsSold) }}</span>
                @if($ticketsChange != 0)
                    <span class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium {{ $ticketsChange > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $ticketsChange > 0 ? '↑' : '↓' }} {{ abs($ticketsChange) }}%
                    </span>
                @endif
            </dd>
            <div class="mt-4 flex space-x-1">
                @for($i = 0; $i < 6; $i++)
                    <div class="h-{{ 4 + ($i * 2) }} w-6 rounded bg-purple-{{ 200 + ($i * 100) }}"></div>
                @endfor
            </div>
        </div>

        <!-- Active Events -->
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Active Events</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $activeEvents }}</dd>
            <div class="mt-4">
                <div class="flex -space-x-1">
                    @foreach($activeManagers->take(3) as $manager)
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-teal-500 text-xs font-medium text-white ring-2 ring-white">
                            {{ $manager->initials }}
                        </span>
                    @endforeach
                    @if($activeManagers->count() > 3)
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-300 text-xs font-medium text-gray-600 ring-2 ring-white">
                            +{{ $activeManagers->count() - 3 }}
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-500">Event managers active</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-5 sm:px-6">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Recent Orders</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-500">View all</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        #{{ substr($order->order_number, -4) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $order->buyer_name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $order->tickets->first()?->ticketType?->name ?? 'N/A' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">£{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No orders yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sales by Ticket Category -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Sales by Ticket Category</h3>
            </div>
            <div class="px-4 pb-5 sm:px-6">
                @forelse($salesByCategory as $category)
                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">{{ $category->name }}</span>
                            <span class="text-sm font-semibold text-teal-600">{{ $category->count }}</span>
                        </div>
                        <div class="mt-1 h-2 w-full rounded-full bg-gray-200">
                            <div class="h-2 rounded-full bg-teal-500" style="width: {{ min(100, ($category->count / max(1, $ticketsSold)) * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No sales data yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bottom cards -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Upcoming Event -->
        @if($upcomingEvent)
            <div class="overflow-hidden rounded-lg bg-gradient-to-r from-teal-600 to-teal-700 shadow">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Upcoming: {{ $upcomingEvent->title }}</h3>
                            <p class="mt-1 text-sm text-teal-100">
                                {{ $upcomingEvent->start_date->diffForHumans() }}. 
                                {{ $upcomingEvent->capacity_percentage }}% capacity reached.
                            </p>
                            <a href="{{ route('admin.events.show', $upcomingEvent) }}" class="mt-4 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-teal-700 shadow-sm hover:bg-teal-50">
                                Manage Event
                            </a>
                        </div>
                        <svg class="h-16 w-16 text-teal-400 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <!-- Export Financial Report -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Export Financial Report</h3>
                        <p class="mt-1 text-sm text-gray-500">Generate a comprehensive CSV or PDF for this quarter.</p>
                        <a href="{{ route('admin.reports.revenue') }}" class="mt-4 inline-flex items-center rounded-md bg-teal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                            Download Report
                        </a>
                    </div>
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
