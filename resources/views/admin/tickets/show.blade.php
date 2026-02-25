@extends('layouts.admin')

@section('title', 'Ticket ' . $ticket->ticket_number)

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-gray-700">Tickets</a></li>
            <li class="flex items-center">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                <span class="ml-2 text-gray-500">{{ $ticket->ticket_number }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Ticket Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl font-bold text-gray-900">Ticket Details</h1>
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $ticket->status === 'valid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ticket Number</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900">{{ $ticket->ticket_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">UUID</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-500">{{ $ticket->uuid }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $ticket->order->event->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ticket Type</dt>
                            <dd class="mt-1"><span class="inline-flex rounded-full bg-teal-100 px-2 py-1 text-xs font-semibold text-teal-800">{{ $ticket->ticketType->name }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Attendee Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $ticket->attendee_name ?? $ticket->order->buyer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Attendee Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $ticket->attendee_email ?? $ticket->order->buyer_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order</dt>
                            <dd class="mt-1"><a href="{{ route('admin.orders.show', $ticket->order) }}" class="text-sm text-teal-600 hover:text-teal-500">{{ $ticket->order->order_number }}</a></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Purchased</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Check-in Status -->
            <div class="rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="font-semibold text-gray-900">Check-in Status</h2>
                </div>
                <div class="p-6">
                    @if($ticket->is_checked_in)
                        <div class="flex items-center rounded-lg bg-green-50 p-4">
                            <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div class="ml-4">
                                <p class="font-semibold text-green-800">Checked In</p>
                                <p class="text-sm text-green-700">{{ $ticket->checked_in_at->format('F d, Y \a\t H:i:s') }}</p>
                                @if($ticket->checked_in_by)
                                    <p class="text-sm text-green-600">By: {{ $ticket->checked_in_by }} ({{ ucfirst($ticket->check_in_method) }})</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center rounded-lg bg-yellow-50 p-4">
                            <svg class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div class="ml-4">
                                <p class="font-semibold text-yellow-800">Not Yet Checked In</p>
                                <p class="text-sm text-yellow-700">This ticket has not been used.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- QR Code -->
        <div>
            <div class="rounded-lg bg-white p-6 shadow text-center">
                <h2 class="mb-4 font-semibold text-gray-900">QR Code</h2>
                @if($ticket->qr_code_path)
                    <img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code" class="mx-auto w-48">
                @else
                    <div class="mx-auto flex h-48 w-48 items-center justify-center rounded-lg bg-gray-100">
                        <span class="text-gray-400">No QR Code</span>
                    </div>
                @endif
                <a href="{{ route('admin.tickets.pdf', $ticket) }}" class="mt-4 inline-flex items-center rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                    ↓ Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
