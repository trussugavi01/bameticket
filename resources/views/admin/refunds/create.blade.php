@extends('layouts.admin')

@section('title', 'Process Refund')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="rounded-lg bg-white p-6 shadow">
        <div class="mb-6 flex items-center">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <h1 class="ml-4 text-xl font-bold text-gray-900">Confirm Refund</h1>
        </div>

        <p class="mb-6 text-sm text-gray-600">Are you sure you want to initiate a manual refund? This action is tracked for financial compliance and audit purposes.</p>

        <!-- Transaction Details -->
        <div class="mb-6 rounded-lg bg-gray-50 p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500">Transaction Details</span>
                <span class="text-sm font-semibold text-teal-600">{{ $order->order_number }}</span>
            </div>
            <dl class="mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Customer Name</dt>
                    <dd class="font-medium text-gray-900">{{ $order->buyer_name }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Original Amount</dt>
                    <dd class="text-xl font-bold text-gray-900">£{{ number_format($order->total_amount, 2) }}</dd>
                </div>
                @if($order->refunded_amount > 0)
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Already Refunded</dt>
                        <dd class="font-medium text-red-600">-£{{ number_format($order->refunded_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 text-sm">
                        <dt class="text-gray-500">Refundable Amount</dt>
                        <dd class="font-bold text-gray-900">£{{ number_format($order->refundable_amount, 2) }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <form action="{{ route('admin.refunds.store', $order) }}" method="POST" x-data="{ type: 'full' }">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Refund Type</label>
                <div class="mt-2 flex gap-4">
                    <label class="flex cursor-pointer items-center rounded-lg border-2 px-4 py-3" :class="type === 'full' ? 'border-teal-500 bg-teal-50' : 'border-gray-200'">
                        <input type="radio" name="type" value="full" x-model="type" class="sr-only">
                        <span class="font-medium" :class="type === 'full' ? 'text-teal-700' : 'text-gray-700'">Full Refund</span>
                    </label>
                    <label class="flex cursor-pointer items-center rounded-lg border-2 px-4 py-3" :class="type === 'partial' ? 'border-teal-500 bg-teal-50' : 'border-gray-200'">
                        <input type="radio" name="type" value="partial" x-model="type" class="sr-only">
                        <span class="font-medium" :class="type === 'partial' ? 'text-teal-700' : 'text-gray-700'">Partial Refund</span>
                    </label>
                </div>
            </div>

            <div class="mb-6" x-show="type === 'partial'" x-cloak>
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount to Refund (£)</label>
                <input type="number" name="amount" id="amount" step="0.01" min="0.01" max="{{ $order->refundable_amount }}" placeholder="0.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>

            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Refund <span class="text-red-500">*</span></label>
                <textarea name="reason" id="reason" rows="3" required placeholder="Explain why this refund is being processed..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.orders.show', $order) }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    ✓ Process Refund
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
