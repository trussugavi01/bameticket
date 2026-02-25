@extends('layouts.admin')

@section('title', 'Revenue Analytics')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Revenue Analytics</h1>
            <p class="mt-1 text-sm text-gray-500">Monitor your ticket sales revenue and performance metrics.</p>
        </div>
        <div class="mt-4 flex gap-3 sm:mt-0">
            <select onchange="window.location.href='?period=' + this.value" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="12_months" {{ $period === '12_months' ? 'selected' : '' }}>Last 12 Months</option>
                <option value="30_days" {{ $period === '30_days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="7_days" {{ $period === '7_days' ? 'selected' : '' }}>Last 7 Days</option>
            </select>
            <a href="{{ route('admin.reports.export') }}" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                ↓ Export Report
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4">
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
            <dd class="mt-1 text-3xl font-semibold text-teal-600">£{{ number_format($totalRevenue, 2) }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Avg. Order Value</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">£{{ number_format($avgOrderValue, 2) }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Tickets Sold</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($ticketsSold) }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Refund Rate</dt>
            <dd class="mt-1 text-3xl font-semibold {{ $refundRate > 5 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($refundRate, 1) }}%</dd>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 rounded-lg bg-white p-6 shadow">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Revenue Over Time</h3>
            <div class="h-64 flex items-end justify-between gap-2">
                @foreach($revenueOverTime as $data)
                    <div class="flex flex-1 flex-col items-center">
                        <div class="w-full bg-teal-500 rounded-t" style="height: {{ max(10, min(200, ($data->gross / max(1, $totalRevenue)) * 500)) }}px"></div>
                        <span class="mt-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($data->month)->format('M') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex items-center justify-center gap-6 text-sm">
                <span class="flex items-center"><span class="mr-2 h-3 w-3 rounded bg-teal-500"></span> Gross Revenue</span>
                <span class="flex items-center"><span class="mr-2 h-3 w-3 rounded bg-teal-300"></span> Net Revenue</span>
            </div>
        </div>

        <!-- Ticket Distribution -->
        <div class="rounded-lg bg-white p-6 shadow">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Ticket Distribution</h3>
            @php $colors = ['bg-teal-500', 'bg-purple-500', 'bg-blue-500', 'bg-orange-500', 'bg-pink-500']; @endphp
            @foreach($ticketDistribution as $index => $category)
                <div class="mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center">
                            <span class="mr-2 h-3 w-3 rounded {{ $colors[$index % count($colors)] }}"></span>
                            {{ $category->name }}
                        </span>
                        <span class="font-semibold">{{ $category->count }}</span>
                    </div>
                    <div class="mt-1 h-2 w-full rounded-full bg-gray-200">
                        <div class="h-2 rounded-full {{ $colors[$index % count($colors)] }}" style="width: {{ min(100, ($category->count / max(1, $ticketsSold)) * 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top Events -->
    <div class="rounded-lg bg-white shadow">
        <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold text-gray-900">Top Performing Events</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Tickets Sold</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($topEvents as $event)
                    <tr>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.events.show', $event) }}" class="font-medium text-teal-600 hover:text-teal-500">{{ $event->title }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $event->tickets_count }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">£{{ number_format($event->orders_sum_total_amount ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">No event data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
