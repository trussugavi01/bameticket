<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmed - NBHCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50">
    <div class="flex min-h-full flex-col items-center justify-center py-12 px-4">
        <div class="mx-auto max-w-md text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h1 class="mt-6 text-3xl font-bold text-gray-900">Order Confirmed!</h1>
            
            @if($order)
                <p class="mt-4 text-gray-600">Thank you for your purchase. Your order <strong>{{ $order->order_number }}</strong> has been confirmed.</p>
                <p class="mt-2 text-sm text-gray-500">A confirmation email with your tickets has been sent to <strong>{{ $order->buyer_email }}</strong>.</p>
                
                <div class="mt-8 rounded-lg bg-white p-6 shadow">
                    <h2 class="font-semibold text-gray-900">Order Summary</h2>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Event</dt>
                            <dd class="font-medium text-gray-900">{{ $order->event->title }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Tickets</dt>
                            <dd class="font-medium text-gray-900">{{ $order->tickets->count() }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2">
                            <dt class="font-medium text-gray-900">Total Paid</dt>
                            <dd class="font-bold text-teal-600">£{{ number_format($order->total_amount, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            @else
                <p class="mt-4 text-gray-600">Your payment was successful. You will receive a confirmation email shortly with your tickets.</p>
            @endif
            
            <div class="mt-8">
                <a href="{{ route('home') }}" class="text-sm font-medium text-teal-600 hover:text-teal-500">
                    ← Return to homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
