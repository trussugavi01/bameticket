@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Customer Database</h1>

    <!-- Search -->
    <div class="rounded-lg bg-white p-4 shadow">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-500">Search</button>
        </form>
    </div>

    <!-- Customers table -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Total Spent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Last Order</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-medium text-gray-600">
                                    {{ strtoupper(substr($customer->buyer_name, 0, 2)) }}
                                </span>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $customer->buyer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $customer->buyer_email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $customer->order_count }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">£{{ number_format($customer->total_spent, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($customer->last_order)->diffForHumans() }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.customers.show', $customer->buyer_email) }}" class="text-teal-600 hover:text-teal-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No customers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($customers->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">{{ $customers->links() }}</div>
        @endif
    </div>
</div>
@endsection
