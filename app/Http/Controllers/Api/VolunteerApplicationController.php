<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VolunteerEvent;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VolunteerApplicationController extends Controller
{
    /**
     * Mendaftarkan user ke sebuah event. (User terotentikasi)
     */
    public function apply(Request $request, VolunteerEvent $event)
    {
        if ($event->status !== 'registration_open') {
            return response()->json(['error' => 'Registration for this event is closed.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'motivation' => 'required|string|min:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();

        // Cek apakah user sudah pernah mendaftar
        $existingApplication = VolunteerApplication::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingApplication) {
            return response()->json(['error' => 'You have already applied for this event.'], 409);
        }

        $application = VolunteerApplication::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'motivation' => $request->motivation,
            'status' => 'pending', // Status awal
        ]);

        return response()->json([
            'message' => 'Successfully applied for the event.',
            'application' => $application,
        ], 201);
    }

    /**
     * Menampilkan daftar pendaftar untuk sebuah event. (Hanya Admin/HR)
     */
    public function listApplicationsForEvent(VolunteerEvent $event)
    {
        $applications = $event->applications()->with('user:id,name,email')->paginate(15);
        return response()->json($applications);
    }

    /**
     * Mengubah status pendaftaran (misal: diterima/ditolak). (Hanya Admin/HR)
     */
    public function updateApplicationStatus(Request $request, VolunteerApplication $application)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $application->update(['status' => $request->status]);

        // Di sini Anda bisa menambahkan notifikasi email ke user
        // Contoh: Mail::to($application->user->email)->send(new ApplicationStatusUpdated($application));

        return response()->json([
            'message' => 'Application status updated successfully.',
            'application' => $application,
        ]);
    }

    /**
     * Menampilkan semua pendaftaran milik user yang sedang login. (User terotentikasi)
     */
    public function myApplications()
    {
        $applications = Auth::user()->volunteerApplications()->with('event:id,title,status')->get();
        return response()->json($applications);
    }
}