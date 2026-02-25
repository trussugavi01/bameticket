@extends('layouts.admin')

@section('title', 'Edit - ' . $event->title)

@section('content')
<div class="mx-auto max-w-4xl">
    <nav class="mb-4 flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.events.index') }}" class="text-gray-500 hover:text-gray-700">Events</a></li>
            <li class="flex items-center">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-gray-500">Edit Event</span>
            </li>
        </ol>
    </nav>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
        <p class="mt-1 text-sm text-gray-600">Update event details for {{ $event->title }}</p>
    </div>

    <form action="{{ route('admin.events.update', $event) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-lg bg-white p-6 shadow">
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">Event Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $event->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ old('category', $event->category) === $category->slug ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                    </div>
                </div>

                <div>
                    <label for="venue_name" class="block text-sm font-medium text-gray-700">Venue Name</label>
                    <input type="text" name="venue_name" id="venue_name" value="{{ old('venue_name', $event->venue_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                </div>

                <div>
                    <label for="venue_address" class="block text-sm font-medium text-gray-700">Venue Address</label>
                    <textarea name="venue_address" id="venue_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('venue_address', $event->venue_address) }}</textarea>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="doors_open" class="block text-sm font-medium text-gray-700">Doors Open</label>
                        <input type="time" name="doors_open" id="doors_open" value="{{ old('doors_open', $event->doors_open?->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="dinner_time" class="block text-sm font-medium text-gray-700">Dinner Time</label>
                        <input type="time" name="dinner_time" id="dinner_time" value="{{ old('dinner_time', $event->dinner_time?->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="dress_code" class="block text-sm font-medium text-gray-700">Dress Code</label>
                        <input type="text" name="dress_code" id="dress_code" value="{{ old('dress_code', $event->dress_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.events.show', $event) }}" class="text-sm font-medium text-gray-600 hover:text-gray-500">Cancel</a>
            <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
