<x-app-layout>
    <!-- resources/views/admin/assign_route.blade.php -->
<form method="POST" action="{{ route('admin.assign.route') }}">
    @csrf

    <!-- Select Driver -->
    <div>
        <label for="driver_id">Driver</label>
        <select id="driver_id" name="driver_id" required>
            @foreach($drivers as $driver)
                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Select Route -->
    <div>
        <label for="route_id">Route</label>
        <select id="route_id" name="route_id" required>
            @foreach($routes as $route)
                <option value="{{ $route->id }}">{{ $route->start_point }} to {{ $route->end_point }}</option>
            @endforeach
        </select>
    </div>

    <!-- Submit Button -->
    <div>
        <button type="submit">Assign Route</button>
    </div>
</form>
</x-app-layout>