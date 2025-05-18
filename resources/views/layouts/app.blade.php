<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Meta Tags (CRUCIAL for modal stability) -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

    <!-- Single jQuery Load (pick one version) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fix for Modal Shaking -->
    <style>
        .modal {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        body.modal-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
            padding-right: 0 !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style type="text/css">
        .modal {
            z-index: 1050;
        }

        .modal-backdrop {
            z-index: 1040;
            /* Ensure it is behind the modal */
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Select an option",
            allowClear: true
        });

        $('.datatable').DataTable({
            responsive: true, // Enables responsive behavior
            paging: true, // Enables pagination
            searching: true, // Enables search functionality
            ordering: true, // Enables column sorting
            info: true, // Shows table info
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ], // Dropdown for number of entries
            language: {
                search: "Filter records:", // Custom search label
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoFiltered: "(filtered from _MAX_ total entries)"
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Apply Select2 to all select elements inside modals
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('select.select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).closest('.modal'),
                    width: '100%',
                    placeholder: "Select an option",
                    allowClear: true
                });
            });
        });

        // Fix Bootstrap focus issue for all modals
        $.fn.modal.Constructor.prototype._enforceFocus = function() {};
    });
</script>

</html>
