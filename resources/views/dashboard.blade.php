<x-app-layout>
    <style type="text/css">
        .ticket_card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
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
                        @foreach($startfrom as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-5">
                    <label for="destination" class="form-label">Destination</label>
                    <select name="destination" class="form-select select2" required>
                        <option value="">Select Destination</option>
                        @foreach($destination as $location)
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
        <div class="mt-3" >
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
                    <input class="form-control me-2" type="search" name="search" placeholder="Search by Bus Number" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
                </div>
            </nav>
            
            <div class="row">
                @foreach($booking_requests as $request)
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
                        <div class="card shadow mb-4 ticket_card">
                            <div class="card-header text-white text-sm" style="background-color: #7545cf">
                                <h5 class="mb-0">Bus Number: <b>{{ $request->bus_number ?? 'N/A' }}</b></h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Seat Number</th>
                                            <th>Payment Status</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groupedTickets as $busNumber => $tickets)
                                            @foreach($tickets as $ticket)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($ticket['date'] ?? 'now')->format('d-m-Y') }}</td>
                                                    <td>{{ $ticket['seat_number'] ?? 'N/A' }}</td> 
                                                    <td class="text-capitalize">
                                                        <span class="badge {{ $request->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ $request->payment_status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $ticket['price'] }}</td>
                                                    <td class="text-capitalize">
                                                        <span class="badge 
                                                            @if($request->status == 'approved') bg-success 
                                                            @elseif($request->status == 'rejected') bg-danger 
                                                            @elseif($request->status == 'pending') bg-warning text-dark 
                                                            @else bg-secondary 
                                                            @endif">
                                                            {{ $request->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <strong>Total Amount: ${{ $totalAmount }}</strong>
                                @if ($request->payment_status != "paid" && $request->status != "rejected")
                                    <div>
                                        <form action="{{ route('payBooking', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Pay</button>
                                        </form>
                                        <form action="{{ route('cancelBooking', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge bg-success">Paid</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination links -->
            {{-- <div class="d-flex justify-content-around">
                {{ $booking_requests->links() }} <!-- Pagination Links -->
            </div> --}}
            <div class="d-flex justify-content-around">
                {{ $booking_requests->links('pagination') }} <!-- Use your custom pagination view -->
            </div>
            
            
        </div>
       
    </div>

     <!-- Ticket Booking Modal -->
     <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
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
                            <input type="date" class="form-control" name="date" id="date" required/>
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


    {{-- <script>
        getLocation();
        function getLocation() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
          } else {
            document.getElementById("location").innerHTML = "Geolocation is not supported by this browser.";
          }
        }
    
        function showPosition(position) {
          var lat = position.coords.latitude;
          var lon = position.coords.longitude;
          
          // Update the iframe src with the user's live location
          var iframeSrc = "https://www.google.com/maps?q=" + lat + "," + lon + "&z=14&output=embed";
          document.getElementById("mapFrame").src = iframeSrc;
    
          // Optionally show the latitude and longitude on the page
        //   document.getElementById("location").innerHTML = "Latitude: " + lat + "<br>Longitude: " + lon;
        }
    
        function showError(error) {
          switch(error.code) {
            case error.PERMISSION_DENIED:
              document.getElementById("location").innerHTML = "User denied the request for Geolocation.";
              break;
            case error.POSITION_UNAVAILABLE:
              document.getElementById("location").innerHTML = "Location information is unavailable.";
              break;
            case error.TIMEOUT:
              document.getElementById("location").innerHTML = "The request to get user location timed out.";
              break;
            case error.UNKNOWN_ERROR:
              document.getElementById("location").innerHTML = "An unknown error occurred.";
              break;
          }
        }
    </script> --}}

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />


    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>


    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


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
            iconSize: [30, 30],  // size of the icon
            iconAnchor: [15, 30],  // anchor point (position relative to the icon)
            popupAnchor: [0, -30]  // position of the popup relative to the icon
        });

        let busIcon = new L.Icon({
            iconUrl: '/images/bus.png', // path to the bus icon
            iconSize: [40, 40],  // size of the icon
            iconAnchor: [20, 40],  // anchor point (position relative to the icon)
            popupAnchor: [0, -40]  // position of the popup relative to the icon
        });

        // Get Passenger Location
        function getPassengerLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    (position) => {
                        let lat = position.coords.latitude;
                        let lon = position.coords.longitude;

                        if (!passengerMarker) {
                            passengerMarker = L.marker([lat, lon], { icon: humanIcon }).addTo(map)
                                .bindPopup("Your Location").openPopup();
                        } else {
                            passengerMarker.setLatLng([lat, lon]);
                        }

                        map.setView([lat, lon], 12);
                    },
                    (error) => console.log("Error getting location:", error),
                    { enableHighAccuracy: true }
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
                            let busMarker = L.marker([bus.latitude, bus.longitude], { icon: busIcon })
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
        $(document).ready(function() {
          
            $('.select2').select2({
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });

        });
        $(document).ready(function () {

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

                            let busMarker = L.marker(latLng, { icon: busIcon })
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
                        $('#bus-list').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
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
                                html = `<tr><td colspan="4" class="text-center text-danger">No buses found.</td></tr>`;
                            }

                            $('#bus-list').html(html);

                            // Fetch latitude and longitude for the searched buses
                            fetchAllActiveBusesAndFilter(buses.map(bus => bus.id));
                        } else {
                            $('#bus-list').html(`<tr><td colspan="4" class="text-center text-danger">No buses found.</td></tr>`);
                            // Clear the map if no buses are found
                            updateMapWithSearchedBuses([]);
                        }
                    },
                    error: function() {
                        $('#bus-list').html(`<tr><td colspan="4" class="text-center text-danger">Something went wrong.</td></tr>`);
                    }
                });
            });
           

            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 3000); // 3 seconds
            let selectedSeats = [];

            // Show modal and fetch available seats when "Add Ticket" is clicked
            $(document).on("click", ".add-ticket-btn", function () {
                // console.log("Add Ticket button clicked"); // Debugging log
                let busId = $(this).data("bus-id");
                $("#busIdInput").val(busId);

                // Fetch available seats
                $.ajax({
                    url: `/passengers/buses/${busId}/seats`,
                    type: "GET",
                    success: function (data) {
                        // console.log("API Response:", data); // Debugging log

                        let ticketTableBody = $("#ticketTableBody");
                        ticketTableBody.empty();

                        if (data.seats) {
                            data.seats.forEach((seat, index) => {
                                let isNewRow = index % 4 === 0; // New row every 4 items
                                let isGapColumn = index % 4 === 2; // Gap after every 2 seats
                                let isBooked = seat.seat_status === "fit"; // Check if the seat is already booked

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

                                if (isGapColumn) ticketTableBody.append('<td style="border: none;"></td>');
                                if ((index + 1) % 4 === 0) ticketTableBody.append('</tr>');
                            });

                            if (data.seats.length % 4 !== 0) ticketTableBody.append('</tr>');
                        }
                    },
                    error: function () {
                        alert("Error fetching seat data.");
                    },
                });
            });


            $(document).on("change", ".seat-checkbox", function () {
                let ticketNumber = $(this).data("ticket");
                let seatNumber = $(this).data("seat");
                let price = parseFloat($(this).data("price")) || 0; // Ensure price is a number

                if ($(this).is(":checked")) {
                    selectedSeats.push({ ticket_number: ticketNumber, seat_number: seatNumber, price: price });
                } else {
                    selectedSeats = selectedSeats.filter((seat) => seat.ticket_number !== ticketNumber);
                }

                // Calculate the total amount dynamically
                let totalAmount = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);

                // Update the price input field dynamically
                $('#price').val(totalAmount.toFixed(2)); // Format to 2 decimal places
            });
            
            $("#ticketForm").submit(function (e) {
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
                    success: function (response) {
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
                    error: function (xhr) {
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
