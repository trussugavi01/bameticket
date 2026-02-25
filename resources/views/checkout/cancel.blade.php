<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout Cancelled - NBHCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50">
    <div class="flex min-h-full flex-col items-center justify-center py-12 px-4">
        <div class="mx-auto max-w-md text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100">
                <svg class="h-10 w-10 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
            
            <h1 class="mt-6 text-3xl font-bold text-gray-900">Checkout Cancelled</h1>
            <p class="mt-4 text-gray-600">Your order was not completed. No payment has been taken.</p>
            
            <div class="mt-8 flex flex-col gap-4">
                <a href="{{ route('checkout.show', $event) }}" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                    Try Again
                </a>
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-gray-500">
                    Return to homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
