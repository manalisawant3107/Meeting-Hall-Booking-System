<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hall;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show($id)
    {
        $hall = Hall::with('images')->findOrFail($id);
        
        // Format Operating Hours
        $start = \Carbon\Carbon::parse($hall->start_time)->format('h:i A');
        $end = \Carbon\Carbon::parse($hall->end_time)->format('h:i A');
        $operatingHours = "$start - $end";

        // Format Available Days
        $availableDays = implode(', ', $hall->available_days ?? []);

        return view('booking', compact('hall', 'availableDays', 'operatingHours'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'hall_id' => 'required|exists:halls,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $hallId = $request->hall_id;
        $date = $request->booking_date;
        $start = $request->start_time;
        $end = $request->end_time;

        $hall = Hall::findOrFail($hallId);

        // 1. Validate Day of Week
        $dayOfWeek = \Carbon\Carbon::parse($date)->format('l'); // Monday, Tuesday...
        if (!in_array($dayOfWeek, $hall->available_days ?? [])) {
            return response()->json(['available' => false, 'message' => "This hall is not available on {$dayOfWeek}s."]);
        }

        // 2. Validate Operating Hours
        $reqStart = \Carbon\Carbon::parse($start);
        $reqEnd = \Carbon\Carbon::parse($end);
        $hallStart = \Carbon\Carbon::parse($hall->start_time);
        $hallEnd = \Carbon\Carbon::parse($hall->end_time);

        // Check if request time is strictly within operating hours
        
        if ($start < $hall->start_time || $end > $hall->end_time) {
             return response()->json(['available' => false, 'message' => "Booking time must be within operating hours ({$hall->start_time} - {$hall->end_time})."]);
        }

        $exists = Booking::where('hall_id', $hallId)
            ->where('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })
            ->exists();

        return response()->json(['available' => !$exists]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'hall_id' => 'required|exists:halls,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ], [
            'booking_date.after_or_equal' => 'You cannot book for a past date.',
            'end_time.after' => 'End time must be after the start time.',
        ]);

        $hallId = $request->hall_id;
        $date = $request->booking_date;
        $start = $request->start_time;
        $end = $request->end_time;
        
        $hall = Hall::findOrFail($hallId);

        // 1. Validate Day of Week (Backend Constraint)
        $dayOfWeek = \Carbon\Carbon::parse($date)->format('l');
        if (!in_array($dayOfWeek, $hall->available_days ?? [])) {
             return back()->withErrors(['error' => "This hall is not available on {$dayOfWeek}s."]);
        }

        // 2. Validate Operating Hours
        if ($start < $hall->start_time || $end > $hall->end_time) {
            return back()->withErrors(['error' => "Booking time must be within operating hours ({$hall->start_time} - {$hall->end_time})."]);
        }

        // Re-check Availability
        $exists = Booking::where('hall_id', $hallId)
            ->where('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Slot is not available for the selected time.']);
        }

        // Calculate Cost
        $startTime = \Carbon\Carbon::parse($start);
        $endTime = \Carbon\Carbon::parse($end);
        $hours = $endTime->diffInMinutes($startTime) / 60;
        $totalPrice = $hours * $hall->price_per_hour;

        Booking::create([
            'hall_id' => $hallId,
            'user_name' => 'Guest User', // Placeholder since auth/input not strictly required by prompt but DB needs it
            'user_email' => 'guest@example.com',
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'total_price' => $totalPrice,
            'status' => 'confirmed'
        ]);

        return redirect()->route('home')->with('success', 'Your hall has been booked successfully!');
    }
}
