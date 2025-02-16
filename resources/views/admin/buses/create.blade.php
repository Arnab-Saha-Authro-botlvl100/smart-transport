<x-app-layout>

    <div class="container">
        <h1>Add New Bus</h1>
        <form action="{{ route('admin.buses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="bus_number">Bus Number</label>
                <input type="text" class="form-control" id="bus_number" name="bus_number" required>
            </div>
            <div class="form-group">
                <label for="route">Route</label>
                <input type="text" class="form-control" id="route" name="route" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" required>
            </div>
            <button type="submit" class="btn btn-success">Add Bus</button>
        </form>
    </div>
    @endsection
    
</x-app-layout>