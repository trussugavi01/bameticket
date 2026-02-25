@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
            + Add User
        </a>
    </div>

    <!-- Filters -->
    <div class="rounded-lg bg-white p-4 shadow">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <select name="role" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-500">Filter</button>
        </form>
    </div>

    <!-- Users table -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Last Login</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-500 text-sm font-medium text-white">{{ $user->initials }}</span>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex rounded-full bg-teal-100 px-2 py-1 text-xs font-semibold text-teal-800">
                                {{ $user->roles->first()?->name ?? 'No Role' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $user->department ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                            {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-teal-600 hover:text-teal-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
