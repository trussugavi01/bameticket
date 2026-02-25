@extends('layouts.admin')

@section('title', 'Refund Audit Log')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Refund Audit Log</h1>
            <p class="mt-1 text-sm text-gray-500">Monitor and audit all refund transactions for financial transparency.</p>
        </div>
        <a href="{{ route('admin.refunds.export') }}" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
            ↓ Export Log (CSV)
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4">
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Refunded This Month</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">£{{ number_format($refundedThisMonth, 2) }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Refund Rate</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $refundRate }}%</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Open Claims</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $openClaims }}</dd>
        </div>
        <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="text-sm font-medium text-gray-500">Avg. Process Time</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($avgProcessTime, 1) }}h</dd>
        </div>
    </div>

    <!-- Refunds table -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Refund ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date/Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Processed By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($refunds as $refund)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-teal-600">{{ $refund->refund_number }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <a href="{{ route('admin.orders.show', $refund->order) }}" class="text-sm text-teal-600 hover:text-teal-500">{{ $refund->order->order_number }}</a>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $refund->order->buyer_name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">£{{ number_format($refund->amount, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $refund->type === 'full' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($refund->type) }}
                            </span>
                        </td>
                        <td class="max-w-xs truncate px-6 py-4 text-sm text-gray-500" title="{{ $refund->reason }}">{{ Str::limit($refund->reason, 30) }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $refund->created_at->format('M d, Y H:i') }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-teal-500 text-xs font-medium text-white">{{ $refund->processedBy?->initials ?? '?' }}</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $refund->processedBy?->name ?? 'System' }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">No refunds found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($refunds->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">{{ $refunds->links() }}</div>
        @endif
    </div>
</div>
@endsection
