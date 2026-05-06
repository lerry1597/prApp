<?php

use Illuminate\Support\Facades\Route;

Route::post('/api/capture-location', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Location Capture Attempt', ['data' => $request->all(), 'ip' => $request->ip()]);

    $data = $request->validate([
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
    ]);
    
    session([
        'user_latitude' => $data['lat'],
        'user_longitude' => $data['lng'],
        'location_captured_at' => now()->toDateTimeString(),
    ]);

    \Illuminate\Support\Facades\Log::info('Location Captured Successfully', ['lat' => $data['lat'], 'lng' => $data['lng']]);
    
    return response()->json(['status' => 'success']);
})->middleware(['web'])->name('capture.location');
