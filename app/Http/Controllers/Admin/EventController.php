<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\AuditLog;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount(['orders', 'ticketTypes'])
            ->withSum('orders', 'total_amount');

        // Filter by tab
        $tab = $request->get('tab', 'upcoming');
        
        switch ($tab) {
            case 'past':
                $query->where('end_date', '<', now());
                break;
            case 'drafts':
                $query->where('status', 'draft');
                break;
            default: // upcoming
                $query->where('start_date', '>', now())
                    ->where('status', '!=', 'draft');
                break;
        }

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(10);

        // Stats
        $totalSales = Order::paid()->whereMonth('created_at', now()->month)->sum('total_amount');
        $ticketsIssued = Ticket::whereMonth('created_at', now()->month)->count();
        $activeCampaigns = Event::whereIn('status', ['published', 'selling'])->count();

        return view('admin.events.index', compact('events', 'tab', 'totalSales', 'ticketsIssued', 'activeCampaigns'));
    }

    public function create()
    {
        $categories = EventCategory::active()->ordered()->get();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:events',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
            'dress_code' => 'nullable|string|max:100',
            'doors_open' => 'nullable|date_format:H:i',
            'dinner_time' => 'nullable|date_format:H:i',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['status'] = 'draft';

        $event = Event::create($validated);

        AuditLog::log('event.created', $event, null, $event->toArray(), 'medium');

        return redirect()->route('admin.events.ticket-types', $event)
            ->with('success', 'Event created. Now configure ticket types.');
    }

    public function show(Event $event)
    {
        $event->load(['ticketTypes', 'orders.tickets', 'sponsors']);
        
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = EventCategory::active()->ordered()->get();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:events,slug,' . $event->id,
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
            'dress_code' => 'nullable|string|max:100',
            'doors_open' => 'nullable|date_format:H:i',
            'dinner_time' => 'nullable|date_format:H:i',
        ]);

        $previousState = $event->toArray();
        $event->update($validated);

        AuditLog::log('event.updated', $event, $previousState, $event->toArray(), 'low');

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $previousState = $event->toArray();
        $event->delete();

        AuditLog::log('event.deleted', $event, $previousState, null, 'high');

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function ticketTypes(Event $event)
    {
        $event->load('ticketTypes');
        return view('admin.events.ticket-types', compact('event'));
    }

    public function storeTicketTypes(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_types' => 'required|array|min:1',
            'ticket_types.*.name' => 'required|string|max:255',
            'ticket_types.*.price' => 'required|numeric|min:0',
            'ticket_types.*.quantity_available' => 'required|integer|min:1',
            'ticket_types.*.early_bird_end_date' => 'nullable|date',
            'ticket_types.*.early_bird_price' => 'nullable|numeric|min:0',
        ]);

        // Delete existing ticket types and create new ones
        $event->ticketTypes()->delete();
        
        $totalCapacity = 0;
        foreach ($validated['ticket_types'] as $index => $typeData) {
            $event->ticketTypes()->create([
                'name' => $typeData['name'],
                'price' => $typeData['price'],
                'quantity_available' => $typeData['quantity_available'],
                'early_bird_end_date' => $typeData['early_bird_end_date'] ?? null,
                'early_bird_price' => $typeData['early_bird_price'] ?? null,
                'sort_order' => $index,
            ]);
            $totalCapacity += $typeData['quantity_available'];
        }

        // Update event total capacity
        $event->update(['total_capacity' => $totalCapacity]);

        return redirect()->route('admin.events.review', $event)
            ->with('success', 'Ticket types configured.');
    }

    public function review(Event $event)
    {
        $event->load('ticketTypes');
        return view('admin.events.review', compact('event'));
    }

    public function publish(Event $event)
    {
        $previousState = $event->toArray();
        
        $event->update(['status' => 'selling']);

        AuditLog::log('event.published', $event, $previousState, $event->toArray(), 'high');

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event is now live and selling tickets!');
    }

    public function archive(Event $event)
    {
        $previousState = $event->toArray();
        
        $event->update(['status' => 'archived']);

        AuditLog::log('event.archived', $event, $previousState, $event->toArray(), 'medium');

        return redirect()->route('admin.events.index')
            ->with('success', 'Event archived successfully.');
    }
}
