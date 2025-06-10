<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VolunteerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VolunteerEventController extends Controller
{
    /**
     * Menampilkan daftar semua event volunteer. (Publik)
     */
    public function index()
    {
        // Tampilkan event yang akan datang atau sedang buka pendaftaran
        $events = VolunteerEvent::whereIn('status', ['upcoming', 'registration_open'])
            ->orderBy('start_date', 'asc')
            ->paginate(10);

        return response()->json($events);
    }

    /**
     * Menyimpan event baru. (Hanya Admin/HR)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:upcoming,registration_open,closed,finished',
            'banner_image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $validator->validated();
        $validatedData['slug'] = Str::slug($validatedData['title'] . '-' . now()->timestamp);

        $event = VolunteerEvent::create($validatedData);

        return response()->json([
            'message' => 'Volunteer event created successfully',
            'event' => $event,
        ], 201);
    }

    /**
     * Menampilkan detail satu event. (Publik)
     */
    public function show(VolunteerEvent $event)
    {
        return response()->json($event);
    }

    /**
     * Mengubah data event. (Hanya Admin/HR)
     */
    public function update(Request $request, VolunteerEvent $event)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'status' => 'sometimes|required|in:upcoming,registration_open,closed,finished',
            'banner_image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event->update($validator->validated());

        return response()->json([
            'message' => 'Volunteer event updated successfully',
            'event' => $event,
        ]);
    }

    /**
     * Menghapus event. (Hanya Admin/HR)
     */
    public function destroy(VolunteerEvent $event)
    {
        $event->delete();
        return response()->json(['message' => 'Volunteer event deleted successfully'], 200);
    }
}