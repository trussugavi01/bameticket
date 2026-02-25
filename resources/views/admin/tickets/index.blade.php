@extends('layouts.admin')

@section('title', 'Tickets')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Ticket Sales</h1>

    <!-- Filters -->
    <div class="rounded-lg bg-white p-4 shadow">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tickets..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <select name="event" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">All Events</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event') == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
                @endforeach
            </select>
            <select name="checked_in" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">All Check-in Status</option>
                <option value="yes" {{ request('checked_in') === 'yes' ? 'selected' : '' }}>Checked In</option>
                <option value="no" {{ request('checked_in') === 'no' ? 'selected' : '' }}>Not Checked In</option>
            </select>
            <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-500">Filter</button>
        </form>
    </div>

    <!-- Tickets table -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Ticket</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Attendee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Check-in</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $ticket->ticket_number }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($ticket->uuid, 8) }}...</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $ticket->order->event->title ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex rounded-full bg-teal-100 px-2 py-1 text-xs font-semibold text-teal-800">{{ $ticket->ticketType->name ?? 'Standard' }}</span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $ticket->attendee_name ?? $ticket->order->buyer_name }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $ticket->status === 'valid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($ticket->is_checked_in)
                                <span class="text-sm text-green-600">✓ {{ $ticket->checked_in_at->format('H:i') }}</span>
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-teal-600 hover:text-teal-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">No tickets found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($tickets->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">{{ $tickets->links() }}</div>
        @endif
    </div>
</div>
@endsection
