<x-app-layout>
    <div class="container">
        <form id="addRouteForm" method="POST" action="{{ route('admin.routes.update', $route->id) }}">
            @csrf
            @method('PUT') {{-- Since it's an update operation --}}
            
            <div class="mb-3">
                <label for="routeName" class="form-label">Route Name</label>
                <input type="text" class="form-control" id="routeName" name="routeName" value="{{ $route->routeName }}" required>
            </div>
        
            <div class="mb-3">
                <label for="startLocation" class="form-label">Start Location</label>
                <input type="text" class="form-control" id="startLocation" name="startLocation" value="{{ $route->origin }}" required>
            </div>
        
            <div class="mb-3">
                <label for="endLocation" class="form-label">End Location</label>
                <input type="text" class="form-control" id="endLocation" name="endLocation" value="{{ $route->destination }}" required>
            </div>
        
            <div class="form-group">
                <label for="stops">Stops</label>
                <div id="stops-container">
                    @foreach(json_decode($route->stops, true) as $stop)
                        <input type="text" class="form-control mb-2 stop-input" name="stops[]" value="{{ $stop }}" required>
                    @endforeach
                </div>
                <button type="button" id="add-stop" class="btn btn-secondary mt-2">Add Another Stop</button>
            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Route</button>
            </div>
        </form>
        
    </div>
   


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
</x-app-layout>