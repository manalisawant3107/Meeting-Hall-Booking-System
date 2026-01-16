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

            // Filter by Date Range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);

                if ($startDate->gt($endDate)) {
                    return back()->withErrors(['error' => 'Start date cannot be after end date.'])->withInput();
                }

                // Get all dates in the range
                $datesInRange = [];
                $tempDate = $startDate->copy();
                while ($tempDate->lte($endDate)) {
                    $datesInRange[] = $tempDate->format('l'); // Get day name
                    $tempDate->addDay();
                }
                $datesInRange = array_unique($datesInRange);

                // Filter halls that are available on ALL requested days of the week
                // We use whereJsonContains or similar if available_days is cast to array
                foreach ($datesInRange as $day) {
                    $query->whereJsonContains('available_days', $day);
                }
            } elseif ($request->filled('start_date')) {
                $day = \Carbon\Carbon::parse($request->start_date)->format('l');
                $query->whereJsonContains('available_days', $day);
            }

            // Load all halls by default or with filters
            $halls = $query->paginate(9)->withQueryString();

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
