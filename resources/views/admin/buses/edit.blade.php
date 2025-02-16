<x-app-layout>
    
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Bus</h2>
    
        <form method="POST" action="{{ route('admin.buses.update', $bus->id) }}">
            @csrf
            @method('PUT') <!-- This tells Laravel it's an update -->
    
            <div class="form-group mb-3">
                <label for="busName" class="font-weight-bold">Bus Name</label>
                <input type="text" class="form-control" id="busName" name="busName" value="{{ old('busName', $bus->name) }}" required>
            </div>
    
            <div class="form-group mb-3">
                <label for="busNumber" class="font-weight-bold">Bus Number</label>
                <input type="text" class="form-control" id="busNumber" name="busNumber" value="{{ old('busNumber', $bus->bus_number) }}" required>
            </div>
    
            <div class="form-group mb-3">
                <label for="route" class="font-weight-bold">Route</label>
                <select class="form-control select2" id="route" name="route" required>
                    <option value="">Select Route</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}" {{ $route->id == $bus->route_id ? 'selected' : '' }}>
                             {{ $route->origin }} to {{ $route->destination }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="form-group mb-3">
                <label for="capacity" class="font-weight-bold">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $bus->capacity) }}" required min="1">
            </div>
    
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success px-4 py-2">Update Bus</button>
            </div>
        </form>
    </div>
    
    <!-- Optional: You can add a custom background or shadow to the form -->
    <style>
        .container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    
        .btn-success {
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
    
        .btn-success:hover {
            background-color: #218838;
        }
    
        .form-group label {
            font-size: 1.1rem;
        }
    </style>
    
</x-app-layout>