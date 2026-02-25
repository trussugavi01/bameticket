@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
    </div>

    <!-- Filters -->
    <div class="rounded-lg bg-white p-4 shadow">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders, customers..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>
            <select name="event" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">All Events</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event') == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-500">Filter</button>
        </form>
    </div>

    <!-- Orders table -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Tickets</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-teal-600 hover:text-teal-500">{{ $order->order_number }}</a>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->buyer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->buyer_email }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $order->event->title ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $order->tickets->count() }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">£{{ number_format($order->total_amount, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @switch($order->payment_status)
                                @case('completed')
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">Paid</span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">Pending</span>
                                    @break
                                @case('failed')
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">Failed</span>
                                    @break
                                @default
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">{{ ucfirst($order->payment_status) }}</span>
                            @endswitch
                            @if($order->refund_status !== 'none')
                                <span class="ml-1 inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-semibold text-purple-800">{{ ucfirst($order->refund_status) }} Refund</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-teal-600 hover:text-teal-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($orders->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
