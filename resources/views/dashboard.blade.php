@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/dashboard') }}">
                            Dashboard
                        </a>
                        <a class="nav-link active" href="{{ url('/todos') }}">
                            Todo Details
                        </a>
                    </li>
                    <!-- Add more menu items as needed -->
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-danger" id="logoutBtn">Logout</button>
                </div>
            </div>

            <!-- Dashboard content goes here -->
            <div class="container">
                <p>Welcome to your dashboard!</p>
               
            </div>
        </div>
    </div>
</div>

<!-- logout Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Logout Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-success">
                <p>You are Successfully Logout</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // Logout button click event
        $('#logoutBtn').on('click', function() {
            // Make an AJAX request to logout
            $.ajax({
                type: 'POST',
                url: '{{ route("logout") }}',
                data: {_token: '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(response) {
                    // Show the success modal or handle success in your preferred way
                    $('#successModal').modal('show');

                    // Redirect after the modal is closed, if needed
                    $('#successModal').on('hidden.bs.modal', function () {
                        window.location.href = '/';
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors, show an alert for example
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
@endsection
