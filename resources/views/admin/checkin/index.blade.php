@extends('layouts.admin')

@section('title', 'Check-In Scanner')

@section('content')
<div class="space-y-6" x-data="checkInScanner()">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Check-In Scanner</h1>
            <p class="mt-1 text-sm text-gray-500">Scan QR codes or enter ticket numbers to check in attendees.</p>
        </div>
        <select x-model="selectedEvent" @change="updateStats()" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <option value="">Select Event</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}" {{ request('event') == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
            @endforeach
        </select>
    </div>

    @if($selectedEvent && $stats)
        <!-- Stats -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-4">
            <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500">Total Tickets</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_tickets'] }}</dd>
            </div>
            <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500">Checked In</dt>
                <dd class="mt-1 text-3xl font-semibold text-green-600">{{ $stats['checked_in'] }}</dd>
            </div>
            <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500">Pending</dt>
                <dd class="mt-1 text-3xl font-semibold text-yellow-600">{{ $stats['pending'] }}</dd>
            </div>
            <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="text-sm font-medium text-gray-500">Check-In Rate</dt>
                <dd class="mt-1 text-3xl font-semibold text-teal-600">{{ $stats['percentage'] }}%</dd>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Scanner -->
        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Scan Ticket</h2>
            
            <form @submit.prevent="scanTicket()" class="space-y-4">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Ticket Code / UUID</label>
                    <input type="text" x-model="code" id="code" autofocus placeholder="Scan QR code or enter ticket number..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                </div>
                <button type="submit" :disabled="scanning || !code" class="w-full rounded-md bg-teal-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-teal-500 disabled:bg-gray-300">
                    <span x-show="!scanning">Check In</span>
                    <span x-show="scanning">Processing...</span>
                </button>
            </form>

            <!-- Result -->
            <div x-show="result" x-cloak class="mt-6">
                <div :class="result?.success ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'" class="rounded-lg border-2 p-4">
                    <div class="flex items-center">
                        <template x-if="result?.success">
                            <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </template>
                        <template x-if="!result?.success">
                            <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                        </template>
                        <div class="ml-4">
                            <p class="font-semibold" :class="result?.success ? 'text-green-800' : 'text-red-800'" x-text="result?.message"></p>
                            <template x-if="result?.ticket">
                                <div class="mt-2 text-sm" :class="result?.success ? 'text-green-700' : 'text-red-700'">
                                    <p><strong>Attendee:</strong> <span x-text="result.ticket.attendee"></span></p>
                                    <p><strong>Ticket Type:</strong> <span x-text="result.ticket.type"></span></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Check-ins -->
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Check-ins</h2>
                <button @click="loadRecent()" class="text-sm text-teal-600 hover:text-teal-500">Refresh</button>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                <template x-for="checkin in recentCheckins" :key="checkin.ticket_number">
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                        <div>
                            <p class="font-medium text-gray-900" x-text="checkin.attendee"></p>
                            <p class="text-sm text-gray-500" x-text="checkin.type + ' • ' + checkin.ticket_number"></p>
                        </div>
                        <span class="text-sm text-green-600" x-text="checkin.checked_in_at"></span>
                    </div>
                </template>
                <p x-show="recentCheckins.length === 0" class="text-center text-sm text-gray-500 py-8">No check-ins yet</p>
            </div>
        </div>
    </div>
</div>

<script>
function checkInScanner() {
    return {
        selectedEvent: '{{ request('event') }}',
        code: '',
        scanning: false,
        result: null,
        recentCheckins: [],

        init() {
            this.loadRecent();
        },

        async scanTicket() {
            if (!this.code) return;
            
            this.scanning = true;
            this.result = null;

            try {
                const response = await fetch('{{ route('admin.checkin.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ code: this.code }),
                });

                this.result = await response.json();
                
                if (this.result.success) {
                    this.code = '';
                    this.loadRecent();
                }
            } catch (e) {
                this.result = { success: false, message: 'Network error. Please try again.' };
            }

            this.scanning = false;
            
            // Auto-clear result after 5 seconds
            setTimeout(() => { this.result = null; }, 5000);
        },

        async loadRecent() {
            const url = new URL('{{ route('admin.checkin.recent') }}');
            if (this.selectedEvent) {
                url.searchParams.set('event', this.selectedEvent);
            }

            const response = await fetch(url);
            const data = await response.json();
            this.recentCheckins = data.checkins;
        },

        updateStats() {
            if (this.selectedEvent) {
                window.location.href = '{{ route('admin.checkin.index') }}?event=' + this.selectedEvent;
            }
        }
    }
}
</script>
@endsection
