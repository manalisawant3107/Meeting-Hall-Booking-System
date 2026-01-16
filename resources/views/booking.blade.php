@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('home') }}" class="btn btn-link text-muted mb-3 text-decoration-none"><i class="bi bi-arrow-left me-2"></i>Back to Search</a>
    
    <div class="row g-4">
        <!-- Left Section: Images -->
        <div class="col-lg-7">
            <div class="card p-2 h-100">
                <div id="venueCarousel" class="carousel slide h-100" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($hall->images as $key => $img)
                            <button type="button" data-bs-target="#venueCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner h-100 rounded">
                        @foreach($hall->images as $key => $img)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }} h-100">
                            <img src="{{ $img->image_path }}" 
                                 class="d-block w-100 h-100 object-fit-cover" 
                                 alt="{{ $hall->name }}"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/1200x800?text=Image+Not+Found';">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#venueCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#venueCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Section: Details & Booking -->
        <div class="col-lg-5">
            <div class="card border-0 h-100">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2 text-uppercase">{{ $hall->space_type }}</span>
                        <h2 class="card-title fw-bold">{{ $hall->name }}</h2>
                        <p class="text-muted"><i class="bi bi-geo-alt-fill me-2"></i>{{ $hall->location }}</p>
                        <h3 class="text-primary fw-bold mt-3">${{ number_format($hall->price_per_hour, 2) }} <small class="text-muted fs-6 fw-normal">/ hour</small></h3>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Venue Features</h5>
                        <div class="row g-2 text-muted">
                            <div class="col-6"><i class="bi bi-people me-2 text-primary"></i>{{ $hall->capacity }} Seats</div>
                            <div class="col-6"><i class="bi bi-wifi me-2 text-primary"></i>Free WiFi</div>
                            <div class="col-12 mt-2"><i class="bi bi-info-circle me-2 text-primary"></i>{{ Str::limit($hall->description, 100) }}</div>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light border rounded">
                        <h6 class="fw-bold text-muted mb-2 text-uppercase" style="font-size: 0.8rem;">Availability Info</h6>
                        <div class="row small">
                            <div class="col-12 mb-1">
                                <span class="fw-bold text-dark">Available Time Slots:</span> <span class="text-muted">{{ $operatingHours }}</span>
                            </div>
                            <div class="col-12 mb-1">
                                <span class="fw-bold text-dark">Available Days:</span> <span class="text-muted">{{ $availableDays }}</span>
                            </div>
                            <div class="col-12">
                                <span class="fw-bold text-dark">Available Seats:</span> <span class="text-muted">{{ $hall->capacity }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary opacity-25">

                    <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hall_id" value="{{ $hall->id }}">
                        
                        <h5 class="fw-bold mb-3">Book This Space</h5>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="booking_date" class="form-control" required min="{{ date('Y-m-d') }}" value="{{ request('start_date') ?? request('booking_date') }}">
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>
                        </div>

                        <div class="alert alert-info py-2" role="alert">
                            <small><i class="bi bi-info-circle me-2"></i>Total Cost: <span id="totalCost" class="fw-bold">$0.00</span></small>
                        </div>

                        <!-- Availability Status Message -->
                        <div id="availabilityStatus" class="d-none alert py-2 mb-3"></div>

                        <div class="d-grid gap-2">
                            <button type="button" id="checkAvailabilityBtn" class="btn btn-outline-primary">Check Availability</button>
                            <button type="submit" id="bookBtn" class="btn btn-primary" disabled>Confirm Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkBtn = document.getElementById('checkAvailabilityBtn');
        const bookBtn = document.getElementById('bookBtn');
        const statusDiv = document.getElementById('availabilityStatus');
        const pricePerHour = {{ $hall->price_per_hour }};
        
        checkBtn.addEventListener('click', function() {
            const date = document.querySelector('input[name="booking_date"]').value;
            const start = document.querySelector('input[name="start_time"]').value;
            const end = document.querySelector('input[name="end_time"]').value;
            const hallId = document.querySelector('input[name="hall_id"]').value;
            const token = document.querySelector('input[name="_token"]').value;

            if(!date || !start || !end) {
                alert('Please select date and time range');
                return;
            }

            checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...';
            
            $.ajax({
                url: "{{ route('booking.check') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    hall_id: hallId,
                    booking_date: date,
                    start_time: start,
                    end_time: end
                }),
                success: function(data) {
                    statusDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
                    if(data.available) {
                        statusDiv.classList.add('alert-success');
                        statusDiv.innerHTML = '<small><i class="bi bi-check-circle-fill me-2"></i>Slot is available!</small>';
                        bookBtn.disabled = false;
                    } else {
                        statusDiv.classList.add('alert-danger');
                        const msg = data.message || 'Slot is not available for the selected time.';
                        statusDiv.innerHTML = `<small><i class="bi bi-x-circle-fill me-2"></i>${msg}</small>`;
                        bookBtn.disabled = true;
                    }
                    checkBtn.innerHTML = 'Check Availability';
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    checkBtn.innerHTML = 'Check Availability';
                    statusDiv.classList.remove('d-none', 'alert-success');
                    statusDiv.classList.add('alert-danger');
                    
                    let errorMessage = 'Something went wrong';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    statusDiv.textContent = errorMessage;
                }
            });
        });

        const inputs = document.querySelectorAll('input[name="start_time"], input[name="end_time"]');
        inputs.forEach(input => {
            input.addEventListener('change', calculateCost);
        });

        function calculateCost() {
            const start = document.querySelector('input[name="start_time"]').value;
            const end = document.querySelector('input[name="end_time"]').value;
            
            if(start && end) {
                const startDate = new Date("2000-01-01 " + start);
                const endDate = new Date("2000-01-01 " + end);
                
                let diffInMinutes = (endDate - startDate) / 1000 / 60;
                
                if (diffInMinutes <= 0) {
                    document.getElementById('totalCost').innerText = "$0.00";
                    bookBtn.disabled = true;
                    return;
                }

                const hours = diffInMinutes / 60;
                const total = (hours * pricePerHour).toFixed(2);
                document.getElementById('totalCost').innerText = "$" + total;
            } else {
                document.getElementById('totalCost').innerText = "$0.00";
            }
        }
    });
</script>
@endpush
