<x-app-layout>

    <style type="text/css">
        #DataTables_Table_0_length, #DataTables_Table_0_filter{
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>

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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Assign Bus to Driver</h4>
                    </div>
    
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('admin.assign.bus') }}">
                            @csrf
    
                            <!-- Select Driver -->
                            <div class="mb-3">
                                <label for="driver_id" class="form-label fw-semibold">Driver</label>
                                <select id="driver_id" name="driver_id" class="form-select select2" required>
                                    <option value="" selected disabled>-- Select a Driver --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <!-- Select Bus -->
                            <div class="mb-3">
                                <label for="bus_id" class="form-label fw-semibold">Bus</label>
                                <select id="bus_id" name="bus_id" class="form-select select2" required>
                                    <option value="" selected disabled>-- Select a Bus --</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}">{{ $bus->bus_number }} - {{ $bus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-bold">
                                    Assign Bus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mx-auto mt-5 " style="width: 80%">
        <h4 class="mt-3 p-3 text-center bg-light rounded shadow-sm">Assigned Buses</h4>
    
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped shadow-lg mt-3 datatable">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>Bus Name</th>
                        <th>Bus Number</th>
                        <th>Driver</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Start From</th>
                        <th>Destination</th>
                        <th>Stops</th>
                        <th>Driver</th>
                        <th>Driver Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assigned_buses as $bus)
                    <tr class="text-center">
                        <td>{{ $bus->name }}</td>
                        <td>{{ $bus->bus_number }}</td>
                        <td>{{ $bus->driver_name }}</td>
                        <td>{{ $bus->date }}</td>
                        <td>{{ $bus->time }}</td>
                        <td>{{ $bus->origin }}</td>
                        <td>{{ $bus->destination }}</td>
                        <td>{{ is_array(json_decode(json_decode($bus->stops, true), true)) ?
                         implode(', ', json_decode(json_decode($bus->stops, true), true)) : $bus->stops }}</td>
                        <td>{{ $bus->driver_name }}</td>
                        <td>{{ $bus->driver_phone }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    
</x-app-layout>