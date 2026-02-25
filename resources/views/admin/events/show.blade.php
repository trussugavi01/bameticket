@extends('layouts.admin')

@section('title', $event->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <nav class="mb-2 flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('admin.events.index') }}" class="text-gray-500 hover:text-gray-700">Events</a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 text-gray-500">{{ $event->title }}</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $event->start_date->format('F d, Y') }} at {{ $event->venue_name ?? 'TBC' }}</p>
        </div>
        <div class="mt-4 flex gap-3 sm:mt-0">
            <a href="{{ route('admin.events.edit', $event) }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Edit Event</a>
            @if($event->status === 'draft')
                <form action="{{ route('admin.events.publish', $event) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">Launch Event</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Status badge -->
    <div class="flex items-center gap-4">
        @switch($event->status)
            @case('selling')
                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">● Selling</span>
                @break
            @case('sold_out')
                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800">● Sold Out</span>
                @break
            @case('draft')
                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800">● Draft</span>
                @break
            @default
                <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800">● {{ ucfirst($event->status) }}</span>
        @endswitch
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4">
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">£{{ number_format($event->total_revenue, 2) }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Tickets Sold</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $event->tickets_sold }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Capacity</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $event->capacity_percentage }}%</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Orders</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $event->orders->count() }}</dd>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Event Details -->
        <div class="rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold text-gray-900">Event Details</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->start_date->format('l, F d, Y') }} at {{ $event->start_date->format('H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Venue</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->venue_name ?? 'Not set' }}</dd>
                        @if($event->venue_address)
                            <dd class="text-sm text-gray-500">{{ $event->venue_address }}</dd>
                        @endif
                    </div>
                    @if($event->dress_code)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dress Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->dress_code }}</dd>
                        </div>
                    @endif
                    @if($event->description)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Ticket Types -->
        <div class="rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold text-gray-900">Ticket Types</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($event->ticketTypes as $ticketType)
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $ticketType->name }}</p>
                                <p class="text-sm text-gray-500">{{ $ticketType->quantity_sold }} / {{ $ticketType->quantity_available }} sold</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-teal-600">£{{ number_format($ticketType->current_price, 2) }}</p>
                                @if($ticketType->is_early_bird_active)
                                    <p class="text-xs text-green-600">Early bird active</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-gray-200">
                            <div class="h-2 rounded-full bg-teal-500" style="width: {{ min(100, ($ticketType->quantity_sold / max(1, $ticketType->quantity_available)) * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-sm text-gray-500">
                        No ticket types configured.
                        <a href="{{ route('admin.events.ticket-types', $event) }}" class="text-teal-600 hover:text-teal-500">Add ticket types</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="rounded-lg bg-white shadow">
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold text-gray-900">Recent Orders</h3>
            <a href="{{ route('admin.orders.index', ['event' => $event->id]) }}" class="text-sm font-medium text-teal-600 hover:text-teal-500">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Tickets</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($event->orders->take(5) as $order)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-teal-600">
                                <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $order->buyer_name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $order->tickets->count() }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">£{{ number_format($order->total_amount, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No orders yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
