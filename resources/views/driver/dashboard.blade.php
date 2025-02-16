<x-app-layout>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow border-0 rounded-3">
                    <!-- Header with a stylish gradient -->
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h4 class="mb-0">🚍 Driver Dashboard</h4>
                    </div>
    
                    <div class="card-body">
                        <!-- Hidden Inputs -->
                        <input type="hidden" id="bus_id" value="{{ $bus->id ?? null }}">
                        <input type="hidden" id="driver_id" value="{{ $driver->id }}">
    
                        <!-- Driver Information -->
                        <div class="mb-4 text-center">
                            <h5 class="text-primary">Welcome, {{ $driver->name }}! 👋</h5>
                            <p class="mb-1"><i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $driver->email }}</p>
                            <p><i class="fas fa-phone"></i> <strong>Phone:</strong> {{ $driver->phone ?? 'N/A' }}</p>
                        </div>
    
                        <!-- Assigned Routes Section -->
                        <h5 class="text-secondary"><i class="fas fa-route"></i> Your Assigned Routes</h5>
                        @if($route !== null)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-dark text-white">
                                        <tr>
                                            <th>Start Point</th>
                                            <th>End Point</th>
                                            <th>Stops</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($routes as $route) --}}
                                            <tr>
                                                <td>{{ $route->origin }}</td>
                                                <td>{{ $route->destination }}</td>
                                                <td>{{ implode(', ', json_decode($route->stops, true)) }}</td>
                                            </tr>
                                        {{-- @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No routes assigned to you.</p>
                        @endif
    
                        <!-- Assigned Bus Information -->
                        @if ($bus)
                            <h5 class="mt-4 text-secondary"><i class="fas fa-bus"></i> Your Assigned Bus</h5>
                            <div class="border rounded p-3 bg-light">
                                <p><strong>🚌 Bus Number:</strong> {{ $bus->bus_number }}</p>
                                <p><strong>🚗 Model:</strong> {{ $bus->name }}</p>
                                <p><strong>📅 Date:</strong> {{ $bus->date }}</p>
                                <p><strong>⏰ Time:</strong> {{ $bus->time }}</p>
                                <p><strong>👥 Capacity:</strong> {{ $bus->capacity }}</p>
                            </div>
                        @else
                            <p class="text-muted mt-3">No buses assigned to you.</p>
                        @endif
    
                        <!-- Complete Trip Button -->
                        <div class="text-center mt-4">
                            <button id="completeTrip" class="btn btn-lg text-white" style="background: linear-gradient(90deg, #28a745, #218838);">
                                <i class="fas fa-check-circle"></i> Complete Trip
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <!-- Add this form to your HTML (e.g., in the footer or layout file) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <!-- FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    

    <script>
        document.getElementById('completeTrip').addEventListener('click', function() {
          
            var busId = $('#bus_id').val(); // Get bus ID from hidden input
            
            $.ajax({
                url: "{{ route('driver.remove.location') }}", // Laravel route for removing location
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    bus_id: busId // Assuming busId is defined elsewhere in your script
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message, // Display message from the controller
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Submit the logout form after the user clicks "OK"
                            if (result.isConfirmed) {
                                document.getElementById('logout-form').submit();
                            }
                        });
                    } else {
                        // Show error message using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message, // Display error message from the controller
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors (e.g., server errors)
                    let errorMessage = xhr.responseJSON?.message || 'Failed to complete the trip. Please try again.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                    console.error("Error removing location:", error);
                }
            });
        });
    </script>
    
    <script>
        $(document).ready(function () {

         // Call getLocation every 5 seconds
            const intervalId = setInterval(function() {
                getLocation();
            }, 50000); // 5000 milliseconds = 5 seconds


            function getLocation() {
                // console.log("here");
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(sendLocation, showError);
                } else {
                    console.log("Geolocation is not supported by this browser.");
                }
            }
    
            function sendLocation(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                var busId = $('#bus_id').val(); // Get bus ID from hidden input
                var driverId = $('#driver_id').val();
                $.ajax({
                    url: "{{ route('driver.send.location') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bus_id: busId,
                        driver_id: driverId,
                        latitude: lat,
                        longitude: lon
                    },
                    success: function (response) {
                        console.log("Location sent successfully:", response);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error sending location:", error);
                    }
                });
            }
    
            function showError(error) {
                let errorMessage = "";
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "User denied the request for Geolocation.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "Location information is unavailable.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "The request to get user location timed out.";
                        break;
                    case error.UNKNOWN_ERROR:
                        errorMessage = "An unknown error occurred.";
                        break;
                }
                console.error(errorMessage);
            }
        });
    </script>
    

</x-app-layout>