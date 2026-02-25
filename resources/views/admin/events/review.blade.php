@extends('layouts.admin')

@section('title', 'Review & Launch - ' . $event->title)

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Review & Launch</h1>
            <p class="mt-1 text-sm text-gray-600">Finalize your event details and start selling tickets.</p>
        </div>
        <span class="rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-800">Step 3 of 3: Complete</span>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Buyer Preview -->
        <div>
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Buyer Preview</h2>
            <div class="overflow-hidden rounded-lg bg-white shadow">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="h-48 w-full object-cover">
                @else
                    <div class="flex h-48 items-center justify-center bg-gradient-to-br from-teal-400 to-teal-600">
                        <svg class="h-16 w-16 text-white/50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900">{{ $event->title }}</h3>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <p>📅 {{ $event->start_date->format('l, F d, Y') }} • {{ $event->start_date->format('H:i') }} GMT</p>
                        @if($event->venue_name)
                            <p>📍 {{ $event->venue_name }}, {{ Str::limit($event->venue_address, 40) }}</p>
                        @endif
                    </div>
                    @if($event->description)
                        <p class="mt-4 text-sm text-gray-600">{{ Str::limit($event->description, 150) }}</p>
                    @endif
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-gray-500">Tickets from</span>
                        <span class="text-xl font-bold text-teal-600">£{{ number_format($event->ticketTypes->min('price') ?? 0, 2) }}</span>
                    </div>
                </div>
                <div class="border-t border-gray-100 bg-gray-50 px-6 py-3">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-2 text-sm text-gray-600">Stripe Integration Active</span>
                        <span class="ml-auto text-xs text-green-600">● Connected</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Inventory Summary -->
        <div>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Ticket Inventory Summary</h2>
                <a href="{{ route('admin.events.ticket-types', $event) }}" class="text-sm text-teal-600 hover:text-teal-500">Edit Tickets</a>
            </div>
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Ticket Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Quantity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $totalCapacity = 0; $totalRevenue = 0; @endphp
                        @foreach($event->ticketTypes as $type)
                            @php 
                                $totalCapacity += $type->quantity_available; 
                                $totalRevenue += $type->quantity_available * $type->price;
                            @endphp
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $type->name }}</div>
                                    @if($type->early_bird_end_date)
                                        <div class="text-xs text-gray-500">Early bird until {{ $type->early_bird_end_date->format('M d') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $type->quantity_sold }} / {{ $type->quantity_available }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium text-teal-600">£{{ number_format($type->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Total Capacity</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $totalCapacity }} Tickets</td>
                            <td class="px-6 py-4 text-right font-semibold text-teal-600">£{{ number_format($totalRevenue, 2) }} Est.</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Sales Channel Info -->
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-xs font-medium uppercase text-gray-500">Sales Channel</div>
                    <div class="mt-1 flex items-center">
                        <svg class="h-4 w-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span class="ml-2 font-medium text-gray-900">Public Online Sales</span>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-xs font-medium uppercase text-gray-500">Fees Responsibility</div>
                    <div class="mt-1 flex items-center">
                        <svg class="h-4 w-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                        <span class="ml-2 font-medium text-gray-900">Passed to Buyer</span>
                    </div>
                </div>
            </div>

            <!-- Warning -->
            <div class="mt-4 rounded-lg bg-amber-50 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <p class="ml-3 text-sm text-amber-700">
                        Once launched, the event title and date cannot be changed without notifying ticket holders. Please ensure all details are correct.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6">
        <a href="{{ route('admin.events.ticket-types', $event) }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
            Back to Details
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500">All systems ready for launch</span>
            <form action="{{ route('admin.events.publish', $event) }}" method="POST">
                @csrf
                <button type="submit" class="rounded-md bg-teal-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                    Launch Event & Start Sales
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
