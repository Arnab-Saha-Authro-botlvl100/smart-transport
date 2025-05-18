<x-app-layout>
    <style type="text/css">
        .ticket_card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
        }

        /* Ticket Card Styles */
        .ticket-card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .ticket-card .card-header {
            background-color: #7545cf;
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .ticket-card .card-header h5 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Table Styles */
        .ticket-card .table-responsive {
            overflow-x: auto;
        }

        .ticket-card table {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .ticket-card th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .ticket-card td,
        .ticket-card th {
            padding: 0.75rem;
            vertical-align: middle;
        }

        /* Badge Styles */
        .ticket-card .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.8em;
        }

        .payment-paid {
            background-color: #28a745;
        }

        .payment-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-approved {
            background-color: #28a745;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-rejected {
            background-color: #dc3545;
        }

        /* Footer Styles */
        .ticket-card .card-footer {
            background-color: #f8f9fa;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .ticket-card .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .ticket-card .total-amount {
            font-size: 1rem;
            color: #495057;
        }

        /* Button Styles */
        .ticket-card .btn {
            font-size: 0.85rem;
            padding: 0.375rem 0.75rem;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .ticket-card .btn-pay {
            background-color: #28a745;
            color: white;
            border: none;
            margin-right: 0.5rem;
        }

        .ticket-card .btn-pay:hover {
            background-color: #218838;
        }

        .ticket-card .btn-cancel {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .ticket-card .btn-cancel:hover {
            background-color: #c82333;
        }

        .ticket-card .btn-leave-feedback {
            background-color: #17a2b8;
            color: white;
            border: none;
        }

        .ticket-card .btn-leave-feedback:hover {
            background-color: #138496;
        }

        .ticket-card .btn-view-feedback {
            background-color: transparent;
            color: #17a2b8;
            border: 1px solid #17a2b8;
            margin-left: 0.5rem;
        }

        .ticket-card .btn-view-feedback:hover {
            background-color: #17a2b8;
            color: white;
        }

        .ticket-card .stars {
            color: #ffc107;
            font-size: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .ticket-card .footer-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .ticket-card .action-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .ticket-card .btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .ticket-card .card-header h5 {
                font-size: 1rem;
            }

            .ticket-card table {
                font-size: 0.8rem;
            }

            .ticket-card td,
            .ticket-card th {
                padding: 0.5rem;
            }
        }
    </style>

    <style>
        /* Professional Feedback Modal Styling */
        .feedback-modal .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .feedback-modal .modal-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .feedback-modal .modal-title {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .feedback-modal .modal-body {
            padding: 2rem;
        }

        .feedback-modal .modal-footer {
            border-top: none;
            padding: 1.5rem;
            background: #f8f9fa;
        }

        /* Star Rating - Professional Version */
        .star-rating {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 1.5rem 0;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 2.5rem;
            color: #e0e0e0;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .star-rating label:hover {
            transform: scale(1.1);
        }

        .star-rating input:checked~label,
        .star-rating input:checked+label {
            color: #FFD700;
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.4);
        }

        /* Feedback Textarea */
        .feedback-textarea {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 15px;
            transition: all 0.3s ease;
            min-height: 120px;
        }

        .feedback-textarea:focus {
            border-color: #2575fc;
            box-shadow: 0 0 0 0.25rem rgba(37, 117, 252, 0.15);
        }

        /* Rating Labels */
        .rating-labels {
            display: flex;
            justify-content: space-between;
            margin-top: -10px;
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Submit Button */
        .btn-submit-feedback {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-submit-feedback:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 117, 252, 0.3);
        }

        /* Close Button */
        .btn-close-modal {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }

        /* Animation Fixes */
        .feedback-modal {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out, opacity 0.3s ease;
        }
    </style>

    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert {
            transition: all 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1 !important;
        }

        .alert-success {
            background-color: #f0fff4;
            color: #28a745;
        }

        .alert-danger {
            background-color: #fff0f0;
            color: #dc3545;
        }
    </style>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 alert">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>


    <!-- Success or Error Message Div -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="
          border-left: 5px solid #28a745;
          border-radius: 4px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.05);
          padding: 15px 20px;
          margin-bottom: 20px;
          display: flex;
          align-items: center;
          animation: slideIn 0.3s ease-out;
      ">
            <i class="fas fa-check-circle me-2" style="font-size: 1.2rem;"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="
              background: none;
              opacity: 0.7;
              transition: opacity 0.2s;
          "></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
            style="
          border-left: 5px solid #dc3545;
          border-radius: 4px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.05);
          padding: 15px 20px;
          margin-bottom: 20px;
          display: flex;
          align-items: center;
          animation: slideIn 0.3s ease-out;
      ">
            <i class="fas fa-exclamation-circle me-2" style="font-size: 1.2rem;"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="
              background: none;
              opacity: 0.7;
              transition: opacity 0.2s;
          "></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
            style="
          border-left: 5px solid #dc3545;
          border-radius: 4px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.05);
          padding: 15px 20px;
          margin-bottom: 20px;
          animation: slideIn 0.3s ease-out;
      ">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2" style="font-size: 1.2rem;"></i>
                <h6 class="mb-0">Please fix the following errors:</h6>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"
                    style="
                  background: none;
                  opacity: 0.7;
                  transition: opacity 0.2s;
              "></button>
            </div>
            <ul class="mt-2 mb-0 ps-3" style="list-style-type: none;">
                @foreach ($errors->all() as $error)
                    <li style="position: relative; padding-left: 20px;">
                        <i class="fas fa-angle-right"
                            style="
                      position: absolute;
                      left: 0;
                      top: 50%;
                      transform: translateY(-50%);
                      font-size: 0.8rem;
                      color: #dc3545;
                  "></i>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="container my-5 p-4 bg-white shadow rounded">
        <h2 class="text-center text-primary">Welcome, {{ auth()->user()->name }} 👋</h2>


        <div id="mapFrame" style="width: 100%; height: 580px; border-radius: 8px;"></div>

        {{-- search buses --}}
        <div class="card p-4 mb-4 shadow-sm">
            <div class="card-header text-center bg-primary text-white my-3">
                <h4 class="mb-0">Search Buses</h4>
            </div>
            <form id="bus-search-form" class="row g-3">
                @csrf
                <div class="col-md-5">
                    <label for="startfrom" class="form-label">Start From</label>
                    <select name="startfrom" class="form-select select2" required>
                        <option value="">Select Start From</option>
                        @foreach ($startfrom as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="destination" class="form-label">Destination</label>
                    <select name="destination" class="form-select select2" required>
                        <option value="">Select Destination</option>
                        @foreach ($destination as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" class="form-control">
                </div>

                <div class="col-md-5">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" name="time" class="form-control">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>


        <!-- Results Section with Card and Shadow -->
        <div class="mt-3">
            <div class="card shadow-lg rounded-lg p-4">
                <div class="card-header text-center bg-secondary text-white my-3">
                    <h4 class="mb-0">Available Buses</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="bus-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Bus Number</th>
                                <th>Route</th>
                                <th>Capacity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="bus-list">
                            <tr>
                                <td colspan="4" class="text-center text-muted">No buses found. Please search.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        {{-- tickets and bus cards --}}
        <div class="container mt-4 card">

            <nav class="navbar navbar-light card-header text-center m-3" style="background-color: #e3f2fd;">
                <div class="container-fluid">
                    <a class="navbar-brand">Your Tickets 🎫</a>
                    <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                        <input class="form-control me-2" type="search" name="search"
                            placeholder="Search by Bus Number" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </nav>

            <div class="row">
                @foreach ($booking_requests as $request)
                    @php
                        $totalAmount = 0;
                        $groupedTickets = [];
                        $tickets = json_decode($request->ticket_ids, true);
                        if (!is_array($tickets)) {
                            $tickets = [];
                        }

                        // Group tickets by bus_number
                        foreach ($tickets as $ticket) {
                            $groupedTickets[$request->bus_number][] = $ticket;
                            $totalAmount += $ticket['price'];
                        }
                    @endphp

                    <div class="col-md-6">
                        <div class="card shadow mb-4 ticket-card">
                            <div class="card-header">
                                <h5 class="mb-0">Bus Number: <b>{{ $request->bus_number ?? 'N/A' }}</b></h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Seat</th>
                                                <th>Payment</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groupedTickets as $busNumber => $tickets)
                                                @foreach ($tickets as $ticket)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($ticket['date'] ?? 'now')->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ $ticket['seat_number'] ?? 'N/A' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge payment-status payment-{{ $request->payment_status }}">
                                                                {{ $request->payment_status }}
                                                            </span>
                                                        </td>
                                                        <td>${{ $ticket['price'] }}</td>
                                                        <td>
                                                            <span class="badge status-{{ $request->status }}">
                                                                {{ $request->status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="footer-content">
                                    <strong class="total-amount">Total: ${{ $totalAmount }}</strong>
                                    <div class="action-buttons">
                                        @if ($request->payment_status != 'paid' && $request->status != 'rejected')
                                            <form
                                                action="{{ route('payBooking', ['id' => $request->id, 'amount' => $totalAmount]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-pay">Pay</button>
                                            </form>
                                            <form action="{{ route('cancelBooking', $request->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-cancel">Cancel</button>
                                            </form>
                                        @elseif ($request->payment_status === 'paid')
                                            <span class="badge payment-paid">Paid</span>
                                            @if ($request->feedback_rating)
                                                <div class="feedback-view">
                                                    <span class="stars">
                                                        @stars($request->feedback->rating)
                                                    </span>
                                                    <button class="btn btn-view-feedback" data-bs-toggle="modal"
                                                        data-bs-target="#viewFeedbackModal-{{ $request->id }}">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                </div>
                                            @else
                                                <button class="btn btn-leave-feedback" data-bs-toggle="modal"
                                                    data-bs-target="#feedbackModal"
                                                    data-booking-id="{{ $request->id }}">
                                                    <i class="fas fa-comment"></i> Feedback
                                                </button>
                                            @endif
                                            <!-- View Feedback Modal -->
                                            <div class="modal fade" id="viewFeedbackModal-{{ $request->id }}"
                                                tabindex="-1"
                                                aria-labelledby="viewFeedbackModalLabel-{{ $request->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="viewFeedbackModalLabel-{{ $request->id }}">
                                                                Feedback Details</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            @if ($request->feedback)
                                                                <p><strong>Rating:</strong>
                                                                    {{ $request->feedback->rating }} / 5</p>
                                                                <p><strong>Feedback:</strong></p>
                                                                <p>{{ $request->feedback->content }}</p>
                                                            @else
                                                                <p>No feedback submitted yet.</p>
                                                            @endif
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-around">
                {{ $booking_requests->links('pagination') }} <!-- Use your custom pagination view -->
            </div>


        </div>

    </div>

    <!-- Ticket Booking Modal -->
    <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTicketModalLabel">Book Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('passenger.tickets.booking') }}" method="POST" id="ticketForm">
                    @csrf
                    <div class="modal-body">
                        <!-- Hidden Bus ID -->
                        <input type="hidden" name="bus_id" id="busIdInput" required>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" name="price" id="price" />
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" id="date" required />
                        </div>

                        <!-- Dynamic Ticket and Seat Table -->
                        <div class="mb-3">
                            <label>Seats</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th>Available Seats</th>
                                    </tr>
                                </thead>
                                <tbody id="ticketTableBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Feedback Modal -->
    <div class="modal fade feedback-modal" id="feedbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Share Your Feedback</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="feedbackForm" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" id="formBookingId">

                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h6>How would you rate your experience?</h6>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5" title="Excellent">★</label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" title="Good">★</label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" title="Average">★</label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" title="Poor">★</label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" title="Terrible">★</label>
                            </div>
                            <div class="rating-labels">
                                <span>Terrible</span>
                                <span>Excellent</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="feedbackText" class="form-label fw-semibold">Your feedback helps us
                                improve</label>
                            <textarea class="form-control feedback-textarea" id="feedbackText" name="feedback" rows="4"
                                placeholder="What did you like or dislike? What could we improve?" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-submit-feedback">
                            <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let map = L.map('mapFrame').setView([23.766771, 90.423497], 12); // Default center (Dhaka)
        let passengerMarker;
        let busMarkers = [];

        // Add OpenStreetMap Tile Layer (Free)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Custom Icons
        let humanIcon = new L.Icon({
            iconUrl: '/images/user.png', // path to the human icon
            iconSize: [30, 30], // size of the icon
            iconAnchor: [15, 30], // anchor point (position relative to the icon)
            popupAnchor: [0, -30] // position of the popup relative to the icon
        });

        let busIcon = new L.Icon({
            iconUrl: '/images/bus.png', // path to the bus icon
            iconSize: [40, 40], // size of the icon
            iconAnchor: [20, 40], // anchor point (position relative to the icon)
            popupAnchor: [0, -40] // position of the popup relative to the icon
        });

        // Get Passenger Location
        function getPassengerLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    (position) => {
                        let lat = position.coords.latitude;
                        let lon = position.coords.longitude;

                        if (!passengerMarker) {
                            passengerMarker = L.marker([lat, lon], {
                                    icon: humanIcon
                                }).addTo(map)
                                .bindPopup("Your Location").openPopup();
                        } else {
                            passengerMarker.setLatLng([lat, lon]);
                        }

                        map.setView([lat, lon], 12);
                    },
                    (error) => console.log("Error getting location:", error), {
                        enableHighAccuracy: true
                    }
                );
            } else {
                console.log("Geolocation is not supported.");
            }
        }

        // Fetch Bus Locations from Laravel Route
        function updateBusLocations() {
            fetch("{{ route('get.buses.for.map') }}")
                .then(response => response.json())
                .then(buses => {
                    // Clear previous bus markers
                    busMarkers.forEach(marker => map.removeLayer(marker));
                    busMarkers = [];

                    buses.forEach(bus => {
                        if (bus.latitude && bus.longitude) {
                            let busMarker = L.marker([bus.latitude, bus.longitude], {
                                    icon: busIcon
                                })
                                .addTo(map)
                                .bindPopup(`Bus: ${bus.bus_number}`);
                            busMarkers.push(busMarker);
                        }
                    });
                })
                .catch(error => console.log("Error fetching bus data:", error));
        }

        // Initialize tracking
        getPassengerLocation();
        updateBusLocations();
        setInterval(updateBusLocations, 30000); // Update bus locations every 10 seconds
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const feedbackModal = document.getElementById('feedbackModal');

            feedbackModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const bookingId = button.getAttribute('data-booking-id');

                if (!bookingId) {
                    console.error('No booking ID found');
                    return;
                }

                const form = document.getElementById('feedbackForm');
                console.log(bookingId);
                form.action = "{{ route('feedback.submit', ['booking' => '']) }}/" + bookingId;
                document.getElementById('formBookingId').value = bookingId;

                // Reset form state
                form.reset();
                form.querySelectorAll('.star-rating input[type="radio"]').forEach(star => {
                    star.checked = false;
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('.select2').select2({
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });

        });
        $(document).ready(function() {

            function fetchAllActiveBusesAndFilter(busIds) {
                fetch("{{ route('get.buses.for.map') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Filter the active buses to only include those that match the search results
                        let filteredBuses = data.filter(bus => busIds.includes(bus.bus_id));

                        // Update the map with the filtered buses
                        updateMapWithSearchedBuses(filteredBuses);
                    })
                    .catch(error => console.log("Error fetching bus data:", error));
            }

            function updateMapWithSearchedBuses(buses) {
                // Clear previous bus markers
                busMarkers.forEach(marker => map.removeLayer(marker));
                busMarkers = [];

                if (buses.length > 0) {
                    let bounds = L.latLngBounds();

                    buses.forEach(bus => {
                        if (bus.latitude && bus.longitude) {
                            let latLng = L.latLng(bus.latitude, bus.longitude);
                            bounds.extend(latLng);

                            let busMarker = L.marker(latLng, {
                                    icon: busIcon
                                })
                                .addTo(map)
                                .bindPopup(`Bus: ${bus.bus_number}`);
                            busMarkers.push(busMarker);
                        }
                    });

                    // Adjust the map view to fit all searched buses
                    map.fitBounds(bounds);
                } else {
                    // Clear the map if no buses are found
                    busMarkers.forEach(marker => map.removeLayer(marker));
                    busMarkers = [];
                }
            }


            $('#bus-search-form').submit(function(event) {
                event.preventDefault(); // Prevent form submission

                let formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('search.buses') }}",
                    type: "GET",
                    data: formData,
                    beforeSend: function() {
                        $('#bus-list').html(
                            '<tr><td colspan="4" class="text-center">Loading...</td></tr>');
                    },
                    success: function(response) {
                        if (response.success) {
                            let buses = response.buses;
                            let html = '';

                            if (buses.length > 0) {
                                buses.forEach(bus => {
                                    html += `
                                        <tr>
                                            <td>${bus.bus_number}</td>
                                            <td>${bus.route.origin} → ${bus.route.destination}</td>
                                            <td>${bus.capacity}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary add-ticket-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addTicketModal"
                                                    data-bus-id="${bus.id}">
                                                    Add Ticket
                                                </button>
                                            </td>
                                        </tr>`;
                                });
                            } else {
                                html =
                                    `<tr><td colspan="4" class="text-center text-danger">No buses found.</td></tr>`;
                            }

                            $('#bus-list').html(html);

                            // Fetch latitude and longitude for the searched buses
                            fetchAllActiveBusesAndFilter(buses.map(bus => bus.id));
                        } else {
                            $('#bus-list').html(
                                `<tr><td colspan="4" class="text-center text-danger">No buses found.</td></tr>`
                            );
                            // Clear the map if no buses are found
                            updateMapWithSearchedBuses([]);
                        }
                    },
                    error: function() {
                        $('#bus-list').html(
                            `<tr><td colspan="4" class="text-center text-danger">Something went wrong.</td></tr>`
                        );
                    }
                });
            });


            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 3000); // 3 seconds
            let selectedSeats = [];

            // Show modal and fetch available seats when "Add Ticket" is clicked
            $(document).on("click", ".add-ticket-btn", function() {
                // console.log("Add Ticket button clicked"); // Debugging log
                let busId = $(this).data("bus-id");
                $("#busIdInput").val(busId);

                // Fetch available seats
                $.ajax({
                    url: `/passengers/buses/${busId}/seats`,
                    type: "GET",
                    success: function(data) {
                        // console.log("API Response:", data); // Debugging log

                        let ticketTableBody = $("#ticketTableBody");
                        ticketTableBody.empty();

                        if (data.seats) {
                            data.seats.forEach((seat, index) => {
                                let isNewRow = index % 4 === 0; // New row every 4 items
                                let isGapColumn = index % 4 ===
                                    2; // Gap after every 2 seats
                                let isBooked = seat.seat_status ===
                                    "fit"; // Check if the seat is already booked

                                if (isNewRow) ticketTableBody.append('<tr>');

                                let seatHtml = `<td style="text-align: center;">
                                    <label class="seat-label">
                                        <input type="checkbox" class="seat-checkbox" 
                                            data-ticket="${seat.ticket_number}" 
                                            data-seat="${seat.seat_number}"
                                            data-price="${seat.price}"
                                            ${isBooked ? "checked disabled" : ""}> 
                                        <span class="seat-style ${isBooked ? 'booked-seat' : ''}">
                                            ${seat.seat_number}
                                        </span>
                                    </label>
                                </td>`;

                                ticketTableBody.append(seatHtml);

                                if (isGapColumn) ticketTableBody.append(
                                    '<td style="border: none;"></td>');
                                if ((index + 1) % 4 === 0) ticketTableBody.append(
                                    '</tr>');
                            });

                            if (data.seats.length % 4 !== 0) ticketTableBody.append('</tr>');
                        }
                    },
                    error: function() {
                        alert("Error fetching seat data.");
                    },
                });
            });


            $(document).on("change", ".seat-checkbox", function() {
                let ticketNumber = $(this).data("ticket");
                let seatNumber = $(this).data("seat");
                let price = parseFloat($(this).data("price")) || 0; // Ensure price is a number

                if ($(this).is(":checked")) {
                    selectedSeats.push({
                        ticket_number: ticketNumber,
                        seat_number: seatNumber,
                        price: price
                    });
                } else {
                    selectedSeats = selectedSeats.filter((seat) => seat.ticket_number !== ticketNumber);
                }

                // Calculate the total amount dynamically
                let totalAmount = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);

                // Update the price input field dynamically
                $('#price').val(totalAmount.toFixed(2)); // Format to 2 decimal places
            });

            $("#ticketForm").submit(function(e) {
                e.preventDefault();
                let busId = $("#busIdInput").val();
                let booking_date = $("#date").val();
                let price = $("#price").val();

                if (selectedSeats.length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "No Seats Selected",
                        text: "Please select at least one seat.",
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('passenger.tickets.booking') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bus_id: busId,
                        tickets: selectedSeats,
                        price: price,
                        date: booking_date,
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Tickets booked successfully!",
                            confirmButtonText: "OK",
                        }).then(() => {
                            location.reload(); // Reload page after successful booking
                        });

                        $("#addTicketModal").modal("hide");
                        selectedSeats = [];
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Booking Failed",
                            text: "Error booking tickets. Please try again.",
                        });
                    },
                });
            });


        });
    </script>
</x-app-layout>
