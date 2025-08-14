@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary mb-3">My Reservations</h1>
                    <p class="lead text-muted">Rate your device experience</p>
                </div>

                @foreach($reservations as $reservation)
                    <div class="card mb-4 shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <!-- Device Image -->
                            <div class="col-md-4">
                                <div class="position-relative h-100" style="min-height: 200px;">
                                    <img src="{{asset($reservation->device->ImagePath)}}" 
                                         alt="{{$reservation->device->name}}" 
                                         class="img-fluid w-100 h-100 object-fit-cover">
                                    @if($reservation->confirmation === 'Confirmed')
                                        <span class="position-absolute top-0 start-0 badge bg-success m-3 px-3 py-2 rounded-pill">
                                            <i class="fas fa-check me-1"></i>Confirmed
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Device Info -->
                            <div class="col-md-8">
                                <div class="card-body p-4 h-100 d-flex flex-column">
                                    <div class="flex-grow-1">
                                        <h3 class="card-title fw-bold text-dark mb-3">
                                            {{ $reservation->device->name ?? 'Unknown Device' }}
                                        </h3>
                                        
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Reservation Date</small>
                                                        <span class="fw-semibold">{{ $reservation->date }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Location</small>
                                                        <span class="fw-semibold">{{ $reservation->device->location ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($reservation->confirmation === 'Confirmed')
                                        <div class="mt-auto">
                                            <button class="btn btn-primary btn-lg px-4 py-2 rounded-pill" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rateModal{{ $reservation->id }}">
                                                <i class="fas fa-star me-2"></i>Rate Device
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Rating Modal -->
                    <div class="modal fade" id="rateModal{{ $reservation->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <form action="{{ $reservation->rating ? route('ratings.update', $reservation->rating->id) : route('ratings.store') }}" 
                                    method="POST" dir="ltr">
                                    @csrf
                                    @if($reservation->rating)
                                        @method('PUT')
                                    @endif
                                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                                    <div class="modal-header bg-primary text-white border-0 py-4">
                                        <div>
                                            <h4 class="modal-title fw-bold mb-1">Rate Your Experience</h4>
                                            <p class="mb-0 opacity-75">{{ $reservation->device->name ?? 'Device' }}</p>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body p-4">
                                        <!-- Device Preview Card -->
                                        <div class="card bg-light border-0 rounded-3 mb-4">
                                            <div class="row g-0 align-items-center p-3">
                                                <div class="col-auto">
                                                    <img src="{{asset($reservation->device->ImagePath)}}" 
                                                         alt="{{$reservation->device->name}}" 
                                                         class="rounded-3" 
                                                         style="width: 80px; height: 80px; object-fit: cover;">
                                                </div>
                                                <div class="col ms-3">
                                                    <h6 class="fw-bold mb-1">{{ $reservation->device->name ?? 'Device' }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>{{ $reservation->date }} • 
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $reservation->device->location ?? 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $questions = [
                                                'service_quality' => 'Overall quality of service',
                                                'device_info_clarity' => 'Clarity and accuracy of equipment/lab information',
                                                'search_interface' => 'Effectiveness of search and filter options',
                                                'request_steps_clarity' => 'Clarity and simplicity of booking steps',
                                                'device_condition' => 'Condition and maintenance of devices',
                                                'research_results_quality' => 'Accuracy and reliability of research results',
                                                'device_availability' => 'Device availability at required times',
                                                'response_speed' => 'Speed of responses to inquiries',
                                                'technical_support' => 'Qualification of lab staff',
                                                'research_success' => 'Service helped complete research successfully',
                                                'recommend_service' => 'Would recommend service to others'
                                            ];
                                            $firstQuestion = array_slice($questions, 0, 1, true);
                                            $remainingQuestions = array_slice($questions, 1, null, true);
                                        @endphp

                                        <!-- Primary Rating (Always Visible) -->
                                        <div class="mb-4">
                                            <h5 class="fw-bold mb-3">
                                                <i class="fas fa-star text-warning me-2"></i>Primary Rating
                                            </h5>
                                            
                                            @foreach($firstQuestion as $name => $label)
                                                <div class="card border-primary border-2 mb-3">
                                                    <div class="card-body">
                                                       <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <p class="fw-bold text-primary">{{ $label }}</p>
                                                            <div class="star-rating d-flex gap-2" data-input="{{ $name }}-{{ $reservation->id }}">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fa-regular fa-star fs-4 text-muted" data-value="{{ $i }}"></i>
                                                                @endfor
                                                            </div>
                                                       </div>
                                                        <input 
                                                            type="hidden" 
                                                            name="{{ $name }}" 
                                                            id="{{ $name }}-{{ $reservation->id }}" 
                                                            value="{{ optional($reservation->rating)->{$name} }}" 
                                                            required
                                                        >
                                                        <div class="text-end">
                                                        <small class="text-muted" data-rating-text="{{ $name }}-{{ $reservation->id }}">Click to rate</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>

                                        <!-- Expandable Additional Ratings -->
                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="fw-bold mb-0">
                                                    <i class="fas fa-list text-info me-2"></i>Additional Ratings
                                                </h5>
                                                <button type="button" 
                                                        class="btn btn-outline-info btn-sm rounded-pill px-3" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#additionalRatings{{ $reservation->id }}"
                                                        id="toggleButton{{ $reservation->id }}">
                                                    <i class="fas fa-chevron-down me-1"></i>Show More
                                                </button>
                                            </div>
                                            
                                            <div class="collapse" id="additionalRatings{{ $reservation->id }}">
                                                <div class="row">
                                                    @foreach($remainingQuestions as $name => $label)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card h-100 border-light">
                                                                <div class="card-body p-3">
                                                                    <label class="form-label fw-semibold small mb-2">{{ $label }}</label>
                                                                   <div class="star-rating d-flex gap-1 justify-content-center" data-input="{{ $name }}-{{ $reservation->id }}">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <i class="fa-regular fa-star text-muted" data-value="{{ $i }}" style="cursor:pointer; font-size:1.2rem;"></i>
                                                                        @endfor
                                                                    </div>
                                                                   <input 
                                                                        type="hidden" 
                                                                        name="{{ $name }}" 
                                                                        id="{{ $name }}-{{ $reservation->id }}" 
                                                                        value="{{ optional($reservation->rating)->{$name} }}" 
                                                                        required
                                                                    >
                                                                    <div class="text-center mt-1">
                                                                    <small class="text-muted" data-rating-text="{{ $name }}-{{ $reservation->id }}">Click to rate</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Comments Section -->
                                        <div class="card bg-light border-0 rounded-3">
                                            <div class="card-body">
                                                <label class="form-label fw-bold mb-3">
                                                    <i class="fas fa-comment-alt text-secondary me-2"></i>Additional Comments
                                                </label>
                                                <textarea name="feedback" 
                                                    class="form-control border-0 shadow-sm" 
                                                    rows="4" 
                                                    placeholder="Share your experience, suggestions, or additional feedback...">{{ optional($reservation->rating)->feedback }}
                                                </textarea>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 py-4 px-4">
                                        <button type="button" class="btn btn-light btn-md px-4 me-2" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-success btn-md px-5 rounded-pill">
                                            <i class="fas fa-paper-plane me-2"></i>Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
     .star-hover:hover {
        transform: scale(1.1);
        color: #ffc107 !important;
    } 
    
    .object-fit-cover {
        object-fit: cover;
    }
    
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .modal-xl {
        max-width: 1200px;
    }
    
    @media (max-width: 768px) {
        .modal-xl {
            max-width: 95%;
        }
    }
</style>

<!-- <script>
    document.querySelectorAll('.star-rating').forEach(function(container) {
        const inputName = container.dataset.input;
        const stars = container.querySelectorAll('i');
        const ratingText = document.querySelector(`.rating-text-${inputName}`);

        stars.forEach((star, index) => {
            // Hover effect
            star.addEventListener('mouseenter', function() {
                const value = this.dataset.value;
                highlightStars(stars, value, true);
            });
            
            // Mouse leave
            star.addEventListener('mouseleave', function() {
                const currentValue = document.getElementById(inputName).value;
                if (currentValue) {
                    highlightStars(stars, currentValue, false);
                } else {
                    resetStars(stars);
                }
            });
            
            // Click event
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                document.getElementById(inputName).value = value;
                highlightStars(stars, value, false);
                
                // Update rating text
                if (ratingText) {
                    const ratingTexts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                    ratingText.textContent = ratingTexts[value - 1];
                    ratingText.className = 'text-warning fw-semibold';
                }
            });
        });
        
        // Container mouse leave
        container.addEventListener('mouseleave', function() {
            const currentValue = document.getElementById(inputName).value;
            if (currentValue) {
                highlightStars(stars, currentValue, false);
            } else {
                resetStars(stars);
            }
        });
    });

    function highlightStars(stars, value, isHover) {
        stars.forEach(star => {
            const starValue = parseInt(star.dataset.value);
            star.classList.remove('fa-solid', 'fa-regular', 'text-warning', 'text-muted');
            
            if (starValue <= value) {
                star.classList.add('fa-solid', 'text-warning');
            } else {
                star.classList.add('fa-regular', 'text-muted');
            }
        });
    }

    function resetStars(stars) {
        stars.forEach(star => {
            star.classList.remove('fa-solid', 'text-warning');
            star.classList.add('fa-regular', 'text-muted');
        });
    }

    // Toggle button text change
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(button) {
        const targetId = button.getAttribute('data-bs-target');
        const target = document.querySelector(targetId);
        
        if (target) {
            target.addEventListener('shown.bs.collapse', function() {
                button.innerHTML = '<i class="fas fa-chevron-up me-1"></i>Show Less';
            });
            
            target.addEventListener('hidden.bs.collapse', function() {
                button.innerHTML = '<i class="fas fa-chevron-down me-1"></i>Show More';
            });
        }
    });
</script> -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.star-rating').forEach(function (container) {
    const inputId = container.dataset.input;                         // e.g., "service_quality-42"
    const inputEl = document.getElementById(inputId);                // hidden input for THIS block
    if (!inputEl) return;                                            // safety

    const stars = Array.from(container.querySelectorAll('i[data-value]'));
    const ratingText = container.parentElement.querySelector(`[data-rating-text="${inputId}"]`);

    function render(val) {
      const n = Number(val) || 0;
      stars.forEach(s => {
        const v = Number(s.dataset.value);
        s.classList.toggle('fa-solid', v <= n);
        s.classList.toggle('fa-regular', v > n);
        s.classList.toggle('text-warning', v <= n);
        s.classList.toggle('text-muted', v > n);
      });
    }

    // Hover preview via delegation
    container.addEventListener('mouseover', function (e) {
      if (e.target && e.target.matches('i[data-value]')) {
        render(e.target.dataset.value);
      }
    });

    // Leave group: restore committed value
    container.addEventListener('mouseleave', function () {
      render(inputEl.value);
    });

    // Commit selection
    container.addEventListener('click', function (e) {
      if (e.target && e.target.matches('i[data-value]')) {
        const val = Number(e.target.dataset.value);
        inputEl.value = val;
        render(val);
        if (ratingText) {
          const labels = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
          ratingText.textContent = labels[val - 1];
          ratingText.classList.remove('text-muted');
          ratingText.classList.add('text-warning', 'fw-semibold');
        }
      }
    });

    // Initial state
    render(inputEl.value);
  });
});
</script>


@endsection