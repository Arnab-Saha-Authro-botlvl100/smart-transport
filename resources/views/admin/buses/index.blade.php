<x-app-layout>

    <div class="container">
        <h1>Manage Buses</h1>
        <a href="{{ route('admin.buses.create') }}" class="btn btn-primary">Add Bus</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Bus Number</th>
                    <th>Route</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($buses as $bus)
                <tr>
                    <td>{{ $bus->bus_number }}</td>
                    <td>{{ $bus->route }}</td>
                    <td>{{ $bus->capacity }}</td>
                    <td>
                        <a href="{{ route('admin.buses.edit', $bus->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.buses.destroy', $bus->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>