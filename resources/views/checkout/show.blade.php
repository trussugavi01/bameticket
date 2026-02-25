<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - Get Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-teal-900 py-4">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-700">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-xl font-bold text-white">NBHCA</span>
                </div>
            </div>
        </header>

        <main class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if(session('error'))
                    <div class="mb-6 rounded-md bg-red-50 p-4">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="lg:grid lg:grid-cols-2 lg:gap-12">
                    <!-- Event Info -->
                    <div>
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="h-64 w-full rounded-lg object-cover">
                        @else
                            <div class="flex h-64 items-center justify-center rounded-lg bg-gradient-to-br from-teal-400 to-teal-600">
                                <svg class="h-20 w-20 text-white/30" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                        @endif

                        <h1 class="mt-6 text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
                        
                        <div class="mt-4 space-y-2 text-gray-600">
                            <p class="flex items-center">
                                <svg class="mr-2 h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                {{ $event->start_date->format('l, F d, Y') }} • {{ $event->start_date->format('H:i') }} GMT
                            </p>
                            @if($event->venue_name)
                                <p class="flex items-center">
                                    <svg class="mr-2 h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    {{ $event->venue_name }}
                                </p>
                            @endif
                            @if($event->dress_code)
                                <p class="flex items-center">
                                    <svg class="mr-2 h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" /></svg>
                                    Dress Code: {{ $event->dress_code }}
                                </p>
                            @endif
                        </div>

                        @if($event->description)
                            <div class="mt-6 prose prose-sm text-gray-600">
                                <p>{{ $event->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Ticket Selection -->
                    <div class="mt-10 lg:mt-0">
                        <div class="rounded-lg bg-white p-6 shadow-lg">
                            <h2 class="text-xl font-bold text-gray-900">Select Tickets</h2>
                            
                            <form action="{{ route('checkout.process', $event) }}" method="POST" x-data="ticketForm()" class="mt-6 space-y-6">
                                @csrf

                                <!-- Ticket Types -->
                                <div class="space-y-4">
                                    @foreach($event->ticketTypes as $index => $ticketType)
                                        <div class="rounded-lg border border-gray-200 p-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h3 class="font-medium text-gray-900">{{ $ticketType->name }}</h3>
                                                    @if($ticketType->is_early_bird_active)
                                                        <p class="text-xs text-green-600">Early bird until {{ $ticketType->early_bird_end_date->format('M d') }}</p>
                                                    @endif
                                                    <p class="mt-1 text-lg font-bold text-teal-600">£{{ number_format($ticketType->current_price, 2) }}</p>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <input type="hidden" name="tickets[{{ $index }}][ticket_type_id]" value="{{ $ticketType->id }}">
                                                    <button type="button" @click="decrement({{ $index }})" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-50">−</button>
                                                    <input type="number" name="tickets[{{ $index }}][quantity]" x-model="quantities[{{ $index }}]" min="0" max="{{ min(10, $ticketType->remaining_quantity) }}" class="w-12 rounded-md border-gray-300 text-center text-sm" readonly>
                                                    <button type="button" @click="increment({{ $index }}, {{ min(10, $ticketType->remaining_quantity) }})" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-50">+</button>
                                                </div>
                                            </div>
                                            @if($ticketType->remaining_quantity <= 10 && $ticketType->remaining_quantity > 0)
                                                <p class="mt-2 text-xs text-orange-600">Only {{ $ticketType->remaining_quantity }} left!</p>
                                            @elseif($ticketType->remaining_quantity <= 0)
                                                <p class="mt-2 text-xs text-red-600">Sold out</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Buyer Details -->
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="font-medium text-gray-900">Your Details</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                            <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone (Optional)</label>
                                            <input type="tel" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>

                                <!-- Total & Submit -->
                                <div class="border-t border-gray-200 pt-6">
                                    <div class="flex items-center justify-between text-lg font-bold">
                                        <span>Total</span>
                                        <span class="text-teal-600">£<span x-text="total.toFixed(2)">0.00</span></span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">VAT included where applicable</p>
                                    
                                    <button type="submit" :disabled="total === 0" class="mt-6 w-full rounded-md bg-teal-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-teal-500 disabled:cursor-not-allowed disabled:bg-gray-300">
                                        Proceed to Payment
                                    </button>

                                    <p class="mt-4 text-center text-xs text-gray-500">
                                        <svg class="mr-1 inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                        Secure checkout powered by Stripe
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function ticketForm() {
            const prices = @json($event->ticketTypes->pluck('current_price')->toArray());
            const ticketCount = {{ $event->ticketTypes->count() }};
            return {
                quantities: Array(ticketCount).fill(0),
                get total() {
                    return this.quantities.reduce((sum, qty, i) => sum + (qty * prices[i]), 0);
                },
                increment(index, max) {
                    if (this.quantities[index] < max) {
                        this.quantities[index]++;
                    }
                },
                decrement(index) {
                    if (this.quantities[index] > 0) {
                        this.quantities[index]--;
                    }
                }
            }
        }
    </script>
</body>
</html>
