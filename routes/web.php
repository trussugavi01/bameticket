<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Public checkout routes
Route::get('/events/{event:slug}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/events/{event:slug}/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel/{event:slug}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

// Stripe webhook (no CSRF)
Route::post('/webhook/stripe', [WebhookController::class, 'handleStripe'])->name('webhook.stripe')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', EventCategoryController::class)->except(['show']);
    
    // Events
    Route::resource('events', EventController::class);
    Route::get('events/{event}/ticket-types', [EventController::class, 'ticketTypes'])->name('events.ticket-types');
    Route::post('events/{event}/ticket-types', [EventController::class, 'storeTicketTypes'])->name('events.store-ticket-types');
    Route::get('events/{event}/review', [EventController::class, 'review'])->name('events.review');
    Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
    Route::post('events/{event}/archive', [EventController::class, 'archive'])->name('events.archive');
    
    // Orders
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::post('orders/{order}/resend-confirmation', [OrderController::class, 'resendConfirmation'])->name('orders.resend-confirmation');
    Route::get('orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    
    // Tickets
    Route::resource('tickets', TicketController::class)->only(['index', 'show']);
    Route::get('tickets/{ticket}/pdf', [TicketController::class, 'downloadPdf'])->name('tickets.pdf');
    
    // Refunds
    Route::get('refunds', [RefundController::class, 'index'])->name('refunds.index');
    Route::get('refunds/create/{order}', [RefundController::class, 'create'])->name('refunds.create');
    Route::post('refunds/{order}', [RefundController::class, 'store'])->name('refunds.store');
    Route::get('refunds/export', [RefundController::class, 'export'])->name('refunds.export');
    
    // Reports
    Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    
    // Customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{email}', [CustomerController::class, 'show'])->name('customers.show');
    
    // Users
    Route::resource('users', UserController::class)->except(['show']);
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/maintenance', [SettingsController::class, 'maintenance'])->name('settings.maintenance');
    Route::post('settings/maintenance', [SettingsController::class, 'toggleMaintenance'])->name('settings.toggle-maintenance');
    Route::get('settings/crm', [SettingsController::class, 'crm'])->name('settings.crm');
    Route::post('settings/crm', [SettingsController::class, 'updateCrm'])->name('settings.update-crm');
    
    // Check-In
    Route::get('checkin', [\App\Http\Controllers\Admin\CheckInController::class, 'index'])->name('checkin.index');
    Route::post('checkin/scan', [\App\Http\Controllers\Admin\CheckInController::class, 'scan'])->name('checkin.scan');
    Route::get('checkin/recent', [\App\Http\Controllers\Admin\CheckInController::class, 'recentCheckins'])->name('checkin.recent');
    
    // Audit Logs
    Route::get('audit', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
    Route::get('audit/{auditLog}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('audit.show');
});
