<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Hall::query();

            // Filter by Space Type
            if ($request->filled('type')) {
                $query->where('space_type', $request->type);
            }

            // Filter by Location
            if ($request->filled('location')) {
                $query->whereIn('location', $request->location);
            }

            // Filter by People (Capacity)
            if ($request->filled('people')) {
                $query->where('capacity', '>=', $request->people);
            }

            // Filter by Date (Reservation Logic)
            if ($request->filled('date')) {
                // Logic to filter unavailable halls would go here
            }

            // Return empty collection if no filters are applied
            if ($request->anyFilled(['type', 'location', 'date', 'people'])) {
                $halls = $query->paginate(9)->withQueryString();
            } else {
                $halls = \App\Models\Hall::whereRaw('0 = 1')->paginate(9); 
            }

            // Dynamic Locations for Dropdown
            $locations = Hall::select('location')->distinct()->pluck('location');

            return view('welcome', compact('halls', 'locations'));

        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Error fetching halls: ' . $e->getMessage());
            
            // Return view with empty data and error message
            // We need to pass empty collections to avoid view errors
            $halls = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 9);
            $locations = collect();
            
            return view('welcome', compact('halls', 'locations'))->withErrors(['error' => 'Unable to load venues at this time. Please try again later.']);
        }
    }
}
