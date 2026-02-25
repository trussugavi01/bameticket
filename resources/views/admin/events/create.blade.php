@extends('layouts.admin')

@section('title', 'Create Event')

@section('content')
<div class="mx-auto max-w-4xl">
    <!-- Breadcrumb -->
    <nav class="mb-4 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.events.index') }}" class="text-gray-500 hover:text-gray-700">Events</a></li>
            <li class="flex items-center">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-gray-500">Create New Event</span>
            </li>
        </ol>
    </nav>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Basic Information</h1>
        <p class="mt-1 text-sm text-gray-600">Set the core identity and logistics for the upcoming health and care awards event.</p>
    </div>

    <!-- Progress sidebar -->
    <div class="flex gap-8">
        <div class="w-48 shrink-0">
            <nav class="space-y-1">
                <div class="flex items-center rounded-md bg-teal-50 px-3 py-2 text-sm font-medium text-teal-700">
                    <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-teal-600 text-xs text-white">1</span>
                    Basic Info
                </div>
                <div class="flex items-center px-3 py-2 text-sm font-medium text-gray-500">
                    <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-200 text-xs">2</span>
                    Ticket Types
                </div>
                <div class="flex items-center px-3 py-2 text-sm font-medium text-gray-500">
                    <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-200 text-xs">3</span>
                    Review & Publish
                </div>
            </nav>
        </div>

        <!-- Form -->
        <div class="flex-1">
            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="space-y-6">
                        <!-- Event Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. NBHCA 2024 Awards Ceremony" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Slug & Category -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700">Event Slug</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">nbhca.org/events/</span>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="nbhca-2024-awards" class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Auto-generated from title if left empty</p>
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ old('category') === $category->slug ? 'selected' : '' }}>
                                            {{ $category->icon }} {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" placeholder="Tell your attendees what this event is about..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('description') }}</textarea>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            </div>
                        </div>

                        <!-- Venue -->
                        <div>
                            <label for="venue_name" class="block text-sm font-medium text-gray-700">Venue Name</label>
                            <input type="text" name="venue_name" id="venue_name" value="{{ old('venue_name') }}" placeholder="e.g. InterContinental London - The O2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="venue_address" class="block text-sm font-medium text-gray-700">Venue Address</label>
                            <textarea name="venue_address" id="venue_address" rows="2" placeholder="Full address including postcode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('venue_address') }}</textarea>
                        </div>

                        <!-- Event details -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="doors_open" class="block text-sm font-medium text-gray-700">Doors Open</label>
                                <input type="time" name="doors_open" id="doors_open" value="{{ old('doors_open') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="dinner_time" class="block text-sm font-medium text-gray-700">Dinner Time</label>
                                <input type="time" name="dinner_time" id="dinner_time" value="{{ old('dinner_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="dress_code" class="block text-sm font-medium text-gray-700">Dress Code</label>
                                <input type="text" name="dress_code" id="dress_code" value="{{ old('dress_code') }}" placeholder="e.g. Black Tie / Formal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <button type="submit" name="action" value="draft" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Save Draft
                    </button>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.events.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-500">Cancel</a>
                        <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                            Next: Ticket Types →
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
