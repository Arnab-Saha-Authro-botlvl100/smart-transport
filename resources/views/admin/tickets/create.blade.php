<x-app-layout>

    <div class="container">
        <h1>Ticket Creation</h1>
        <form action="{{ route('admin.tickets.store') }}" method="POST">
            @csrf
            <input type="hidden" name="bus_id" value="{{ $bus->id }}">
        
            <div class="mb-3">
                <label for="seat_number" class="form-label">Seat Number</label>
                <input type="number" class="form-control" id="seat_number" name="seat_number" required>
            </div>
        
            <button type="submit" class="btn btn-primary">Create Ticket</button>
        </form>
        
    </div>

</x-app-layout>