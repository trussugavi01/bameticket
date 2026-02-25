@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <nav class="mb-2 flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">Orders</a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        <span class="ml-2 text-gray-500">{{ $order->order_number }}</span>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Order Details</h1>
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $order->is_paid ? 'PAID' : strtoupper($order->payment_status) }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500">Transaction ID: {{ $order->stripe_payment_intent_id ?? 'N/A' }}</p>
        </div>
        <div class="mt-4 flex gap-3 sm:mt-0">
            <form action="{{ route('admin.orders.resend-confirmation', $order) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    ✉ Resend Confirmation
                </button>
            </form>
            <a href="{{ route('admin.orders.invoice', $order) }}" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                ↓ Download Invoice
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Buyer Information -->
        <div class="rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold text-gray-900">Buyer Information</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="space-y-4">
                    <div class="flex items-start">
                        <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Full Name</dt>
                            <dd class="text-sm text-gray-900">{{ $order->buyer_name }}</dd>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Email Address</dt>
                            <dd class="text-sm text-gray-900">{{ $order->buyer_email }}</dd>
                        </div>
                    </div>
                    @if($order->buyer_phone)
                    <div class="flex items-start">
                        <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Phone</dt>
                            <dd class="text-sm text-gray-900">{{ $order->buyer_phone }}</dd>
                        </div>
                    </div>
                    @endif
                </dl>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900">Payment Summary</h4>
                    <dl class="mt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Subtotal ({{ $order->tickets->count() }} Tickets)</dt>
                            <dd class="text-gray-900">£{{ number_format($order->subtotal, 2) }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">VAT ({{ $order->vat_rate }}%)</dt>
                            <dd class="text-gray-900">£{{ number_format($order->vat_amount, 2) }}</dd>
                        </div>
                        @if($order->transaction_fee > 0)
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Transaction Fee</dt>
                            <dd class="text-gray-900">£{{ number_format($order->transaction_fee, 2) }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between border-t border-gray-200 pt-2 text-sm font-semibold">
                            <dt class="text-gray-900">Total</dt>
                            <dd class="text-teal-600">£{{ number_format($order->total_amount, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Issued Tickets -->
        <div class="lg:col-span-2">
            <div class="rounded-lg bg-white shadow">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-5 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-900">Issued Tickets ({{ $order->tickets->count() }})</h3>
                    <button class="text-sm text-teal-600 hover:text-teal-500">↓ Download All PDF</button>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->tickets as $ticket)
                        <div class="flex items-center justify-between px-4 py-4 sm:px-6">
                            <div class="flex items-center">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-teal-100">
                                    <svg class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">{{ $order->event->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $ticket->ticket_number }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $ticket->is_checked_in ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800' }}">
                                    {{ $ticket->ticketType->name ?? 'Standard' }}
                                </span>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $order->event->start_date->format('M d, Y') }} • {{ $order->event->start_date->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order History -->
            <div class="mt-6 rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-900">Order History</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li class="relative pb-8">
                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                <div class="relative flex space-x-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    </span>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <p class="text-sm text-gray-500">Order Completed & Tickets Issued</p>
                                        <p class="whitespace-nowrap text-sm text-gray-500">{{ $order->paid_at?->format('M d, Y H:i') ?? $order->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </li>
                            <li class="relative pb-8">
                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                <div class="relative flex space-x-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                                    </span>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <p class="text-sm text-gray-500">Payment Confirmed (Stripe)</p>
                                        <p class="whitespace-nowrap text-sm text-gray-500">{{ $order->paid_at?->subMinute()->format('M d, Y H:i') ?? $order->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </li>
                            <li class="relative">
                                <div class="relative flex space-x-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </span>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <p class="text-sm text-gray-500">Checkout Started</p>
                                        <p class="whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->subMinutes(2)->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Refund Button -->
            @if($order->can_refund)
                <div class="mt-6 text-right">
                    <a href="{{ route('admin.refunds.create', $order) }}" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                        Process Refund
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
