<x-app-layout>
    <style type="text/css">
        /* Adjustments for DataTables */
        .dataTables_length, .dataTables_filter {
            margin-top: 10px;
            margin-bottom: 10px;
        }
    
        /* Seat container setup */
        .seat-container {
            text-align: center;
            padding: 5px;
        }
    
        /* Label for seat (clickable area) */
        .seat-label {
            cursor: pointer;
            display: inline-block;
            position: relative;
        }
    
        /* Hide default checkbox */
        .seat-checkbox {
            display: none;
        }
    
        /* Style for seats (default green for available) */
        .seat-style {
            display: inline-block;
            width: 60px;
            height: 60px;
            background-color: #28a745; /* Default green for available seats */
            color: white;
            font-weight: bold;
            border-radius: 15px 15px 5px 5px; /* Rounded top with flat bottom */
            text-align: center;
            padding-top: 15px;
            box-shadow: 2px 4px 5px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, background-color 0.3s;
            position: relative;
            font-size: 16px;
        }
    
        /* Hover effect for seats */
        .seat-style:hover {
            transform: scale(1.1);
        }
    
        /* Booked seat - gray and disabled */
        .booked-seat {
            background-color: gray !important;
            cursor: not-allowed;
            opacity: 0.6;
        }
    
        /* Empty seat - red */
        .empty-seat {
            background-color: red !important;
        }
    
        /* Selected seat - green */
        .selected-seat {
            background-color: #28a745 !important;
        }
    
        /* Ticket and seat number styling */
        .ticket-number {
            font-size: 12px;
            display: block;
        }
    
        .seat-number {
            font-size: 18px;
            font-weight: bold;
        }
    
        /* Seat spacing (gap between seats) */
        .seat-gap {
            width: 20px;
            border: none;
        }
    </style>
    
    <div class="container">
                
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

         <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center bg-dark text-white p-3 rounded">
            <h1 class="mb-0">📊 Admin Dashboard</h1>
            <p class="mb-0">Welcome, Admin!</p>
        </div>

        <!-- Statistics Section -->
        <div class="row mt-4">
            <!-- Total Buses -->
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <h5 class="text-primary">🚌 Total Buses</h5>
                        <p class="fs-4 fw-bold">{{ $totalBuses }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <h5 class="text-success">💰 Total Revenue</h5>
                        <p class="fs-4 fw-bold">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Routes -->
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <h5 class="text-danger">🚏 Active Routes</h5>
                        <p class="fs-4 fw-bold">{{ $activeRoutes }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Options -->
        <div class="row mt-4">
            <!-- Add Bus -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addBusModal">🚌 Add New Bus</button>
                    </div>
                </div>
            </div>

            <!-- Add Route -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addRouteModal">🛤️ Add New Route</button>
                    </div>
                </div>
            </div>

            <!-- Assign Bus to Driver -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <a href="{{ route('admin.assign.bus.form') }}" class="btn btn-warning w-100">👨‍✈️ Assign Bus to Driver</a>
                    </div>
                </div>
            </div>

            <!-- View Routes -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <a 
                        {{-- href="{{ route('admin.routes.index') }}" --}}
                         class="btn btn-info w-100">📍 View All Routes</a>
                    </div>
                </div>
            </div>
        </div>

      
        <div class="table-responsive mt-3 shadow-lg p-3 mb-5 bg-body rounded">
            <!-- Bus List -->
            <div class="d-flex justify-content-between align-items-center bg-secondary text-white p-3 rounded mt-3">
                <h4 class="mb-0">🚌 All Buses</h4>
            </div>
            <table class="table table-hover table-striped datatable">
                <thead class="table-dark text-white">
                    <tr>
                        <th>Bus Number</th>
                        <th>Bus Name</th>
                        <th>Route</th>
                        <th>Stops</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buses as $bus)
                    <tr>
                        <td>{{ $bus->bus_number }}</td>
                        <td>{{ $bus->name }}</td>
                        <td>{{ $bus->route->origin }} - {{ $bus->route->destination }}</td>
                        <td>{{ implode(', ', json_decode($bus->route->stops)) }}</td>
                        <td>{{ $bus->capacity }}</td>
                        <td>
                            <a href="{{ route('admin.buses.edit', $bus->id) }}" class="btn btn-outline-secondary btn-sm">✏️ Edit</a>
                            <form action="{{ route('admin.buses.destroy', $bus->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">🗑️ Delete</button>
                            </form>
                            <button type="button" class="btn btn-outline-success btn-sm add-ticket-btn" data-bs-toggle="modal" data-bs-target="#addTicketModal"
                             data-bus-id="{{ $bus->id }}" data-bus-number="{{ $bus->bus_number }}" data-bus-name="{{ $bus->name }}">
                                🎟️ Add Ticket
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    
     
        <!-- Route List Table -->
        <div class="table-responsive mt-3 shadow-lg p-3 mb-5 bg-body rounded">
            <!-- Section Header -->
            <div class="d-flex justify-content-between align-items-center bg-secondary text-white p-3 rounded mt-3">
                <h4 class="mb-0">🚏 All Routes</h4>
            </div>
            <table class="table table-hover table-striped border shadow-sm datatable" id="route-table">
                <thead class="table-dark text-white">
                    <tr>
                        <th>📍 Start From</th>
                        <th>📍 Destination</th>
                        <th>🛑 Stops</th>
                        <th>⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routes as $route)
                    <tr>
                        <td>{{ $route->origin }}</td>
                        <td>{{ $route->destination }}</td>
                        <td>{{ implode(', ', json_decode($route->stops)) }}</td>
                        <td>
                            <a href="{{ route('admin.routes.edit', $route->id) }}" class="btn btn-outline-primary btn-sm">✏️ Edit</a>
                            <form action="{{ route('admin.routes.destroy', $route->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">🗑️ Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        

       <!-- Add Route Modal -->
        <div class="modal fade" id="addRouteModal" tabindex="-1" aria-labelledby="addRouteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- Larger modal for better spacing -->
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addRouteModalLabel">
                            🛣️ Add New Route
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Add Route Form -->
                        <form id="addRouteForm" method="POST" action="{{ route('routes.store') }}">
                            @csrf
                            
                            <div class="row">
                                <!-- Route Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="routeName" class="form-label fw-bold">
                                        📍 Route Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="routeName" name="routeName" required placeholder="Enter route name">
                                </div>

                                <!-- Start Location -->
                                <div class="col-md-6 mb-3">
                                    <label for="startLocation" class="form-label fw-bold">
                                        🚏 Start Location <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="startLocation" name="startLocation" required placeholder="Enter start location">
                                </div>
                            </div>

                            <div class="row">
                                <!-- End Location -->
                                <div class="col-md-6 mb-3">
                                    <label for="endLocation" class="form-label fw-bold">
                                        🏁 End Location <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="endLocation" name="endLocation" required placeholder="Enter destination">
                                </div>

                                <!-- Stops -->
                                <div class="col-md-6 mb-3">
                                    <label for="stops" class="form-label fw-bold">
                                        🚉 Stops <span class="text-danger">*</span>
                                    </label>
                                    <div id="stops-container">
                                        <input type="text" class="form-control mb-2" name="stops[]" required placeholder="Enter stop">
                                    </div>
                                    <button type="button" id="add-stop" class="btn btn-outline-primary btn-sm mt-2">
                                        ➕ Add Another Stop
                                    </button>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">✅ Save Route</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

         <!-- Modal for Add Bus -->
        <div class="modal fade" id="addBusModal" tabindex="-1" aria-labelledby="addBusModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- Larger Modal for Better Form Visibility -->
                <div class="modal-content rounded-3 shadow-lg">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="addBusModalLabel">🚌 Add New Bus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Add Bus Form -->
                        <form id="addBusForm" method="POST" action="{{ route('buses.store') }}">
                            @csrf

                            <div class="row">
                                <!-- Bus Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="busName" class="form-label fw-bold">
                                        🚌 Bus Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="busName" name="busName" placeholder="Enter bus name" required>
                                </div>

                                <!-- Bus Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="busNumber" class="form-label fw-bold">
                                        🔢 Bus Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="busNumber" name="busNumber" placeholder="Enter bus number" required>
                                </div>

                                <!-- Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label fw-bold">
                                        📅 Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>

                                <!-- Time -->
                                <div class="col-md-6 mb-3">
                                    <label for="time" class="form-label fw-bold">
                                        ⏰ Bus Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control" id="time" name="time" required>
                                </div>

                                <!-- Route Selection -->
                                <div class="col-md-6 mb-3">
                                    <label for="route" class="form-label fw-bold">
                                        🛣️ Route <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" id="route" name="route" required>
                                        <option value="" disabled selected>Select Route</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}">{{ $route->origin }} - {{ $route->destination }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Capacity -->
                                <div class="col-md-6 mb-3">
                                    <label for="capacity" class="form-label fw-bold">
                                        🏋️ Capacity <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="capacity" name="capacity" placeholder="Enter bus capacity" required>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Bus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Ticket Booking Modal -->
        <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- Larger modal for better layout -->
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addTicketModalLabel">
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form action="{{ route('admin.tickets.store') }}" method="POST" id="ticketForm">
                        @csrf
                        <div class="modal-body">
                            <!-- Hidden Bus ID -->
                            <input type="hidden" name="bus_id" id="busIdInput" required>

                            <div class="row">
                                <!-- Select Route -->
                                <div class="col-md-6 mb-3">
                                    <label for="routeofbus" class="form-label fw-bold">
                                        🛣️ Select Route <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" name="route_id" id="routeofbus" required>
                                        <option value="" disabled selected>Select Route</option>
                                        <!-- Routes will be dynamically loaded here -->
                                    </select>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-bold">
                                        💰 Price <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" name="price" id="price" required placeholder="Enter ticket price">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Date Selection -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label fw-bold">
                                        📅 Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="date" id="date" required>
                                </div>
                            </div>

                            <!-- Seat Selection Table -->
                            <div class="mb-3">
                                <label class="fw-bold">🪑 Select Seats / 🎫 Ticket Number</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead class="table-success">
                                            <tr>
                                                <th>Seat 1</th>
                                                <th>Seat 2</th>
                                                <th>Seat 3</th>
                                                <th>Seat 4</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ticketTableBody">
                                            <!-- Seats will be dynamically added here -->
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancel</button>
                            <button type="submit" class="btn btn-success">✅ Launch Tickets</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


      
    </div>

    {{-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApdnBLqJeVW4c5t1Z32v8BzVBVWyJnY1g&libraries=maps,marker&v=beta"
    defer
  ></script> --}}

    <script>
        // Add stop input field dynamically
        document.getElementById('add-stop').addEventListener('click', function() {
            var stopInput = document.createElement('input');
            stopInput.type = 'text';
            stopInput.classList.add('form-control');
            stopInput.name = 'stops[]';
            stopInput.required = true;
            document.getElementById('stops-container').appendChild(stopInput);
        });
    </script>

    <script>
        $(document).ready(function () {
            let selectedSeats = [];

            // // Show modal and fetch available seats when "Add Ticket" is clicked
            // $(".add-ticket-btn").on("click", function () {
            //     let busId = $(this).data("bus-id");
            //     $("#busIdInput").val(busId);

            //     // Fetch available seats and routes
            //     $.ajax({
            //         url: `/admin/buses/${busId}/seats`,
            //         type: "GET",
            //         success: function (data) {
            //             // console.log("API Response:", data);

            //             let ticketTableBody = $("#ticketTableBody");
            //             let routeSelect = $("#routeofbus");

            //             ticketTableBody.empty();
            //             routeSelect.empty(); // Clear existing options before updating

            //             // ✅ Populate Routes Dropdown
            //             if (data.routes) {
            //                 data.routes.forEach(function (route) {
            //                     let selected = route.id === data.selected_route ? "selected" : "";
            //                     routeSelect.append(`<option value="${route.id}" ${selected}>${route.origin} - ${route.destination}</option>`);
            //                 });
            //             }

            //             // ✅ Render Seat Selection (Fixing `data.tickets` to `data.seats`)
            //             if (data.seats) {
            //                 data.seats.forEach((seat, index) => {
            //                     let isNewRow = index % 4 === 0; // New row every 4 items
            //                     let isGapColumn = index % 4 === 2; // Gap after every 2 seats
            //                     let isBooked = seat.seat_status === "fit"; // Check if the seat is already booked

            //                     // Open a new row if it’s the start of a new row
            //                     if (isNewRow) ticketTableBody.append('<tr>');

            //                     let seatHtml = `<td style="text-align: center;">
            //                         <label class="seat-label">
            //                             <input type="checkbox" class="seat-checkbox" 
            //                                 data-ticket="${seat.ticket_number}" 
            //                                 data-seat="${seat.seat_number}"
            //                                 ${isBooked ? "checked disabled" : ""}> 
            //                             <span class="seat-style ${isBooked ? 'booked-seat' : ''}">
            //                                 ${seat.seat_number}
            //                             </span>
            //                         </label>
            //                     </td>`;

            //                     // Add the seat to the table
            //                     ticketTableBody.append(seatHtml);

            //                     // If it’s the gap column, add an empty cell
            //                     if (isGapColumn) ticketTableBody.append('<td style="border: none;"></td>');

            //                     // Close the row every 4 items
            //                     if ((index + 1) % 4 === 0) ticketTableBody.append('</tr>');
            //                 });

            //                 // Ensure the last row is closed properly
            //                 if (data.seats.length % 4 !== 0) ticketTableBody.append('</tr>');
            //             }

            //         },
            //         error: function () {
            //             alert("Error fetching seat data.");
            //         },
            //     });
            // });

            $(".add-ticket-btn").on("click", function () {
                let busId = $(this).data("bus-id");
                let busNumber = $(this).data("bus-number"); // Get bus number from button
                let busname = $(this).data("bus-name"); // Get bus number from button

                $("#busIdInput").val(busId);
                $("#addTicketModalLabel").text(`🎟️ Ticket For ${busname} (${busNumber})`); // Update modal title

                // Fetch available seats and routes
                $.ajax({
                    url: `/admin/buses/${busId}/seats`,
                    type: "GET",
                    success: function (data) {
                        let ticketTableBody = $("#ticketTableBody");
                        let routeSelect = $("#routeofbus");

                        ticketTableBody.empty();
                        routeSelect.empty(); // Clear existing options before updating

                        // ✅ Populate Routes Dropdown
                        if (data.routes) {
                            data.routes.forEach(function (route) {
                                let selected = route.id === data.selected_route ? "selected" : "";
                                routeSelect.append(`<option value="${route.id}" ${selected}>${route.origin} - ${route.destination}</option>`);
                            });
                        }
                        // ✅ Render Seat Selection
                        if (data.seats) {
                            let rowHtml = "";
                            data.seats.forEach((seat, index) => {
                                let isNewRow = index % 4 === 0; // New row every 4 seats
                                let isBooked = seat.seat_status === "fit"; // Already booked (gray)
                                let isEmpty = seat.seat_status === null || seat.seat_status === ""; // Empty seat (red)

                                // Start new row if needed
                                if (isNewRow) rowHtml += "<tr>";

                                let seatHtml = `
                                    <td class="seat-container">
                                        <label class="seat-label">
                                            <input type="checkbox" class="seat-checkbox"
                                                data-ticket="${seat.ticket_number}"
                                                data-seat="${seat.seat_number}"
                                                ${isBooked ? "checked disabled" : ""}> 
                                            <div class="seat-style ${isBooked ? 'booked-seat' : ''} ${isEmpty ? 'empty-seat' : ''}">
                                            <span class="ticket-number">${seat.seat_number}</span><br>
                                            <span class="seat-number text-dark">${seat.ticket_number}</span>
                                            </div>
                                        </label>
                                    </td>`;

                                rowHtml += seatHtml;

                                // Close row after 4 seats
                                if ((index + 1) % 4 === 0 || index === data.seats.length - 1) rowHtml += "</tr>";
                            });

                            $("#ticketTableBody").append(rowHtml);
                        }

                    },
                    error: function () {
                        alert("Error fetching seat data.");
                    },
                });
            });

            
            // ✅ Handle Seat Selection Click Event (Turns Green/Red)
            $(document).on("change", ".seat-checkbox", function () {
                let seatBox = $(this).closest(".seat-container").find(".seat-style");
                let ticketNumber = $(this).data("ticket");
                let seatNumber = $(this).data("seat");

                if ($(this).is(":checked")) {
                    seatBox.removeClass("empty-seat").addClass("selected-seat"); // Turn green when selected
                    selectedSeats.push({ ticket_number: ticketNumber, seat_number: seatNumber });
                } else {
                    seatBox.removeClass("selected-seat").addClass("empty-seat"); // Turn back to red when unchecked
                    selectedSeats = selectedSeats.filter((seat) => seat.ticket_number !== ticketNumber);
                }

                console.log(selectedSeats); // Debugging log
            });

            // AJAX form submission
            $("#ticketForm").submit(function (e) {
                e.preventDefault();
                let busId = $("#busIdInput").val();
                let routeId = $("#routeofbus").val();
                let price = $("#price").val();

                if (selectedSeats.length === 0) {
                    alert("Please select at least one seat.");
                    return;
                }
                

                $.ajax({
                    url: "{{ route('admin.tickets.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bus_id: busId,
                        tickets: selectedSeats,
                        route_id: routeId,
                        price: price,
                    },
                    success: function (response) {
                        alert("Tickets booked successfully!");
                        $("#addTicketModal").modal("hide");
                        selectedSeats = [];
                    },
                    error: function (xhr) {
                        alert("Error booking tickets.");
                    },
                });
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 3000); // 3 seconds
            
            $('#bus-table').DataTable({});

            // Handle form submission
            $('#addBusForm').on('submit', function (e) {
                e.preventDefault(); // Prevent form from submitting the normal way

                var formData = $(this).serialize(); // Get form data

                $.ajax({
                    url: $(this).attr('action'), // URL to submit to (the store route)
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            // Close the modal
                            $('#addBusModal').modal('hide');

                            // SweetAlert Success Message
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // You can update the view with the new route, or refresh a section
                                location.reload(); // Refresh the page to show the new bus
                            });
                        } else {
                            // SweetAlert Error Message
                            Swal.fire({
                                title: 'Failed!',
                                text: 'Failed to add bus.',
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function () {
                        // SweetAlert Error Message
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error while adding bus.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });


            $('#addRouteForm').on('submit', function (e) {
                e.preventDefault(); // Prevent form from submitting the normal way

                var formData = $(this).serialize(); // Get form data

                $.ajax({
                    url: $(this).attr('action'), // URL to submit to (the store route)
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            // Close the modal
                            $('#addRouteModal').modal('hide');
                            
                            // SweetAlert Success Message
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // You can update the view with the new route, or refresh a section
                                location.reload(); // Refresh the page to show the new route
                            });
                        } else {
                            // SweetAlert Error Message
                            Swal.fire({
                                title: 'Failed!',
                                text: 'Failed to add route.',
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function () {
                        // SweetAlert Error Message
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error while adding route.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

        });
    </script>
    
</x-app-layout>