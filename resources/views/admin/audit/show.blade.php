@extends('layouts.admin')

@section('title', 'Audit Log Details')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.audit.index') }}" class="text-gray-500 hover:text-gray-700">Audit Logs</a></li>
            <li class="flex items-center">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                <span class="ml-2 text-gray-500">{{ $auditLog->transaction_id }}</span>
            </li>
        </ol>
    </nav>

    <div class="rounded-lg bg-white shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900">Audit Log Details</h1>
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold
                    @switch($auditLog->impact_level)
                        @case('critical') bg-red-100 text-red-800 @break
                        @case('high') bg-orange-100 text-orange-800 @break
                        @case('medium') bg-yellow-100 text-yellow-800 @break
                        @default bg-gray-100 text-gray-800
                    @endswitch">
                    {{ ucfirst($auditLog->impact_level) }} Impact
                </span>
            </div>
        </div>

        <div class="p-6">
            <dl class="grid grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                    <dd class="mt-1 font-mono text-sm text-gray-900">{{ $auditLog->transaction_id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Action</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->action }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">User</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->user?->name ?? 'System' }} ({{ $auditLog->user_role ?? 'N/A' }})</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date/Time</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->created_at->format('F d, Y H:i:s') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->ip_address ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Request URL</dt>
                    <dd class="mt-1 text-sm text-gray-900 truncate">{{ $auditLog->request_url ?? '—' }}</dd>
                </div>
                @if($auditLog->model_type)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Model Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ class_basename($auditLog->model_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Model ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->model_id }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    @if($auditLog->previous_state || $auditLog->new_state)
        <div class="grid grid-cols-2 gap-6">
            @if($auditLog->previous_state)
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="font-semibold text-gray-900">Previous State</h2>
                    </div>
                    <div class="p-6">
                        <pre class="overflow-auto rounded bg-gray-50 p-4 text-xs">{{ json_encode($auditLog->previous_state, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif

            @if($auditLog->new_state)
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="font-semibold text-gray-900">New State</h2>
                    </div>
                    <div class="p-6">
                        <pre class="overflow-auto rounded bg-gray-50 p-4 text-xs">{{ json_encode($auditLog->new_state, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="text-right">
        <a href="{{ route('admin.audit.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-500">← Back to Audit Logs</a>
    </div>
</div>
@endsection
