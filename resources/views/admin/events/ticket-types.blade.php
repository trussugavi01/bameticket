@extends('layouts.admin')

@section('title', 'Ticket Types - ' . $event->title)

@section('content')
<div class="mx-auto max-w-4xl">
    <!-- Breadcrumb -->
    <nav class="mb-4 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.events.index') }}" class="text-gray-500 hover:text-gray-700">Events</a></li>
            <li class="flex items-center">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-gray-500">New Event Setup</span>
            </li>
        </ol>
    </nav>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Ticket Category Configuration</h1>
        <p class="mt-1 text-sm text-gray-600">Define the pricing levels and ticket availability. These categories will be displayed to attendees during checkout.</p>
    </div>

    <div class="flex gap-8">
        <!-- Progress sidebar -->
        <div class="w-48 shrink-0">
            <nav class="space-y-1">
                <div class="flex items-center px-3 py-2 text-sm font-medium text-gray-500">
                    <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-green-500 text-xs text-white">✓</span>
                    Basic Info
                </div>
                <div class="flex items-center rounded-md bg-teal-50 px-3 py-2 text-sm font-medium text-teal-700">
                    <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-teal-600 text-xs text-white">2</span>
                    Ticket Types
                </div>
                <div class="flex items-center px-3 py-2 text-sm font-medium text-gray-500">
                    <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-200 text-xs">3</span>
                    Review & Publish
                </div>
            </nav>
        </div>

        <!-- Form -->
        <div class="flex-1">
            <form action="{{ route('admin.events.store-ticket-types', $event) }}" method="POST" x-data="ticketTypesForm()" class="space-y-6">
                @csrf

                <template x-for="(ticket, index) in tickets" :key="index">
                    <div class="rounded-lg bg-white p-6 shadow">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="mr-2 h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                                </svg>
                                <span class="font-medium text-gray-900" x-text="ticket.name || 'New Ticket Type'"></span>
                            </div>
                            <button type="button" @click="removeTicket(index)" x-show="tickets.length > 1" class="text-gray-400 hover:text-red-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ticket Name</label>
                                <input type="text" :name="'ticket_types[' + index + '][name]'" x-model="ticket.name" placeholder="e.g. Early Bird - Standard" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price (£)</label>
                                <input type="number" step="0.01" :name="'ticket_types[' + index + '][price]'" x-model="ticket.price" placeholder="125.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Quantity</label>
                                <input type="number" :name="'ticket_types[' + index + '][quantity_available]'" x-model="ticket.quantity_available" placeholder="50" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Early Bird End Date (Optional)</label>
                                <input type="date" :name="'ticket_types[' + index + '][early_bird_end_date]'" x-model="ticket.early_bird_end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Early Bird Price (Optional)</label>
                                <input type="number" step="0.01" :name="'ticket_types[' + index + '][early_bird_price]'" x-model="ticket.early_bird_price" placeholder="99.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Add ticket button -->
                <button type="button" @click="addTicket()" class="flex w-full items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-6 text-center hover:border-teal-400">
                    <div class="text-center">
                        <svg class="mx-auto h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span class="mt-2 block text-sm font-medium text-teal-600">Add another ticket category</span>
                    </div>
                </button>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.events.edit', $event) }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Previous Step
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit" name="action" value="draft" class="rounded-md border border-teal-600 bg-white px-4 py-2 text-sm font-medium text-teal-600 shadow-sm hover:bg-teal-50">
                            Save Draft
                        </button>
                        <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                            Continue to Review →
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function ticketTypesForm() {
    const existingTickets = @json($event->ticketTypes->toArray());
    return {
        tickets: existingTickets.length > 0 ? existingTickets : [{ name: '', price: '', quantity_available: '', early_bird_end_date: '', early_bird_price: '' }],
        addTicket() {
            this.tickets.push({ name: '', price: '', quantity_available: '', early_bird_end_date: '', early_bird_price: '' });
        },
        removeTicket(index) {
            this.tickets.splice(index, 1);
        }
    }
}
</script>
@endsection
