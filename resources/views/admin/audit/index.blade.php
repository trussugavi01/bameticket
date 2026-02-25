@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Audit Log</h1>
        <p class="mt-1 text-sm text-gray-500">Track all administrative actions and system changes.</p>
    </div>

    <!-- Filters -->
    <div class="rounded-lg bg-white p-4 shadow">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="user" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action..." class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <select name="impact" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">All Impact Levels</option>
                <option value="low" {{ request('impact') === 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('impact') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('impact') === 'high' ? 'selected' : '' }}>High</option>
                <option value="critical" {{ request('impact') === 'critical' ? 'selected' : '' }}>Critical</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-500">Filter</button>
        </form>
    </div>

    <!-- Logs table -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Transaction</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Impact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date/Time</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($logs as $log)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-mono text-gray-500">{{ $log->transaction_id }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-100 text-xs font-medium text-teal-700">{{ $log->user?->initials ?? '?' }}</span>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $log->user?->name ?? 'System' }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->user_role ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $log->action }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @switch($log->impact_level)
                                @case('critical')
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">Critical</span>
                                    @break
                                @case('high')
                                    <span class="inline-flex rounded-full bg-orange-100 px-2 py-1 text-xs font-semibold text-orange-800">High</span>
                                    @break
                                @case('medium')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">Medium</span>
                                    @break
                                @default
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">Low</span>
                            @endswitch
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $log->ip_address ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.audit.show', $log) }}" class="text-teal-600 hover:text-teal-900">Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">No audit logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($logs->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
