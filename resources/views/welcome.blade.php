@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Hero Section -->
    <div class="text-center mb-5 mt-3">
        <h1 class="display-4 fw-bold mb-3">Find Your Perfect <span class="text-primary">Meeting Space</span></h1>
        <p class="lead text-muted">Browse, filter, and book meeting halls instantly.</p>
    </div>

    <!-- Filter Section -->
    <div class="card p-4 mb-5 border-0">
        <form action="{{ route('home') }}" method="GET" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-grid-fill me-2"></i>Space Type</label>
                    <select name="type" class="form-select">
                        <option value="">Any Type</option>
                        <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference Room</option>
                        <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training Hall</option>
                        <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting Room</option>
                        <option value="coworking" {{ request('type') == 'coworking' ? 'selected' : '' }}>Co-working Space</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-geo-alt-fill me-2"></i>Location</label>
                    <select name="location[]" id="locationSelect" class="form-select" multiple="multiple">
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ (collect(request('location'))->contains($loc)) ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><i class="bi bi-calendar-event-fill me-2"></i>Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Select Date" min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="bi bi-people-fill me-2"></i>People</label>
                    <input type="number" name="people" class="form-control" placeholder="Ex: 5" min="1" value="{{ request('people') }}">
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search me-2"></i>Find Spaces</button>
                    @if(request()->anyFilled(['type', 'location', 'date', 'people']))
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Listings Section -->
    <h3 class="fw-bold mb-4">Available Venues ({{ $halls->total() }})</h3>
    
    <div class="row g-4" id="venuesList">
        @forelse($halls as $hall)
        <div class="col-12">
            <div class="card overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ $hall->image ?? 'https://via.placeholder.com/800x600?text=No+Image' }}" 
                             class="img-fluid h-100 object-fit-cover" 
                             alt="{{ $hall->name }}" 
                             style="min-height: 200px;" 
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600?text=Image+Not+Found';">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body h-100 d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2 text-uppercase">{{ $hall->space_type }}</span>
                                    <h4 class="card-title fw-bold mb-1">{{ $hall->name }}</h4>
                                    <p class="text-muted mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $hall->location }}</p>
                                </div>
                                <div class="text-end">
                                    <h3 class="text-primary fw-bold mb-0">${{ number_format($hall->price_per_hour, 0) }}<span class="fs-6 text-muted fw-normal">/hr</span></h3>
                                </div>
                            </div>
                            
                            <hr class="border-secondary opacity-25 my-3">
                            
                            <div class="row mb-4">
                                <div class="col-auto">
                                    <i class="bi bi-people text-muted me-2"></i>
                                    <span>{{ $hall->capacity }} Seats</span>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-wifi text-muted me-2"></i>
                                    <span>High-speed WiFi</span>
                                </div>
                                @if($hall->space_type == 'conference' || $hall->space_type == 'training')
                                <div class="col-auto">
                                    <i class="bi bi-projector text-muted me-2"></i>
                                    <span>Projector</span>
                                </div>
                                @endif
                            </div>
                            
                            <p class="text-muted small mb-3 text-truncate">{{ $hall->description }}</p>

                            <div class="mt-auto">
                                <a href="{{ route('hall.show', ['id' => $hall->id] + request()->query()) }}" class="btn btn-primary w-100 stretched-link">View Details & Book</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="py-5">
                <i class="bi bi-search display-1 text-muted opacity-25"></i>
                <h3 class="mt-3 text-muted">No venues found</h3>
                <p class="text-muted">Try adjusting your filters to find what you're looking for.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination Links -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $halls->links() }}
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#locationSelect').select2({
            placeholder: 'Select Locations',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
