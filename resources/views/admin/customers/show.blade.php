@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="space-y-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.customers.index') }}" class="text-gray-500 hover:text-gray-700">Customers</a></li>
            <li class="flex items-center">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                <span class="ml-2 text-gray-500">{{ $customer['name'] }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Customer Info -->
        <div class="rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="font-semibold text-gray-900">Customer Information</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-teal-100 text-xl font-bold text-teal-600">
                        {{ strtoupper(substr($customer['name'], 0, 2)) }}
                    </span>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $customer['name'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $customer['email'] }}</p>
                    </div>
                </div>

                <dl class="mt-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $orders->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Spent</dt>
                        <dd class="mt-1 text-2xl font-semibold text-teal-600">£{{ number_format($orders->sum('total_amount'), 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Tickets</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $orders->sum(fn($o) => $o->tickets->count()) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">First Purchase</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $orders->last()?->created_at->format('M d, Y') ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Orders -->
        <div class="lg:col-span-2">
            <div class="rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="font-semibold text-gray-900">Order History</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-teal-600 hover:text-teal-500">{{ $order->order_number }}</a>
                                <p class="text-sm text-gray-500">{{ $order->event->title }} • {{ $order->tickets->count() }} tickets</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">£{{ number_format($order->total_amount, 2) }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">No orders found</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
