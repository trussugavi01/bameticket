@extends('layouts.admin')

@section('title', 'CRM Integration')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">CRM Integration</h1>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <nav class="space-y-1">
                <a href="{{ route('admin.settings.index') }}" class="flex items-center rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /></svg>
                    General
                </a>
                <a href="{{ route('admin.settings.maintenance') }}" class="flex items-center rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" /></svg>
                    Maintenance
                </a>
                <a href="{{ route('admin.settings.crm') }}" class="flex items-center rounded-md bg-teal-50 px-3 py-2 text-sm font-medium text-teal-700">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                    CRM Integration
                </a>
            </nav>
        </div>

        <!-- Content -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.settings.update-crm') }}" method="POST">
                @csrf
                
                <div class="rounded-lg bg-white shadow">
                    <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900">CRM API Configuration</h3>
                    </div>
                    <div class="space-y-6 px-4 py-5 sm:p-6">
                        <!-- Enable CRM -->
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Enable CRM Integration</label>
                                <p class="text-xs text-gray-500">Sync buyer data with your CRM system</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="crm_enabled" value="1" {{ ($settings['crm_enabled'] ?? false) ? 'checked' : '' }} class="peer sr-only">
                                <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-teal-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300"></div>
                            </label>
                        </div>

                        <!-- API URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CRM API URL</label>
                            <input type="url" name="crm_api_url" value="{{ $settings['crm_api_url'] ?? '' }}" placeholder="https://crm.example.com/api" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>

                        <!-- Sync Options -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="crm_sync_on_checkout" value="1" {{ ($settings['crm_sync_on_checkout'] ?? false) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                <label class="ml-2 block text-sm text-gray-900">Sync buyer data on checkout completion</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="crm_auto_tag_tickets" value="1" {{ ($settings['crm_auto_tag_tickets'] ?? false) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                <label class="ml-2 block text-sm text-gray-900">Automatically tag ticket buyers in CRM</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                        Save CRM Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
