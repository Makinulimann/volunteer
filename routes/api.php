<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VolunteerEventController;
use App\Http\Controllers\Api\VolunteerApplicationController;

// Rute Publik (tidak perlu token)
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

// Rute Terproteksi (wajib menggunakan token dengan middleware 'auth:api')
Route::controller(AuthController::class)->middleware('auth:api')->group(function () {
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('user-profile', 'userProfile');
});

Route::get('volunteer-events', [VolunteerEventController::class, 'index']);
Route::get('volunteer-events/{event:slug}', [VolunteerEventController::class, 'show']); 

Route::middleware('auth:api')->group(function () {
    Route::post('volunteer-events/{event:slug}/apply', [VolunteerApplicationController::class, 'apply']);
    Route::get('my-volunteer-applications', [VolunteerApplicationController::class, 'myApplications']);
});

Route::middleware(['auth:api', 'role:admin,hr'])->group(function () {
    Route::post('volunteer-events', [VolunteerEventController::class, 'store']);
    Route::put('volunteer-events/{event:slug}', [VolunteerEventController::class, 'update']);
    Route::delete('volunteer-events/{event:slug}', [VolunteerEventController::class, 'destroy']);
    
    Route::get('volunteer-events/{event:slug}/applications', [VolunteerApplicationController::class, 'listApplicationsForEvent']);
    Route::patch('volunteer-applications/{application}/status', [VolunteerApplicationController::class, 'updateApplicationStatus']);
});