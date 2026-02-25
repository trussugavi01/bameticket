<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CheckInController;

Route::post('/checkin/{uuid}', [CheckInController::class, 'checkIn'])->name('api.checkin');
Route::get('/validate/{uuid}', [CheckInController::class, 'validate'])->name('api.validate');
