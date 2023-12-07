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
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Todo Details</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-danger" id="logoutBtn" data-token="{{ csrf_token() }}">Logout</button>
                </div>
            </div>

            <!-- todos listing -->
            <div class="container">
                <h1>Todos</h1>
                <a href="{{ route('todos.create') }}" class="btn btn-primary">Create New Todo</a>
                <table class="table mt-3" id="todoTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Is Marked</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todoDetails as $todo)
                            <tr>
                                <td>{{ $todo->id }}</td>
                                <td>{{ $todo->title }}</td>
                                <td>{{ $todo->description }}</td>
                                <td>
                                    @if($todo->image)
                                        <img src="{{ asset('storage/' . $todo->image) }}" alt="Todo Image" style="max-width: 100px;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $todo->i_is_marked == 1 ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('todos.edit', $todo->id) }}" class="btn btn-primary">Edit</a>
                                    <button type="submit" class="btn btn-danger delete-btn" data-id="{{ $todo->id }}" data-token="{{ csrf_token() }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- logout submit before Modal -->
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
<!-- todo delete submit before Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#todoTable').DataTable({
            lengthChange: false
        });
        
        var todoId, csrfToken;

        // Event handler for delete button click
        $('.delete-btn').on('click', function() {
            todoId = $(this).data('id');
            csrfToken = $(this).data('token');

            // Show a confirmation modal
            $('#confirmationModal').modal('show');
        });

        // Event handler for confirm delete button click
        $('#confirmDeleteBtn').on('click', function() {
            // Close the confirmation modal
            $('#confirmationModal').modal('hide');

            // Make an AJAX request for deletion
            $.ajax({
                type: 'DELETE', // or 'POST' depending on your route definition
                url: '/todos/' + todoId, // Update the URL based on your route
                data: {
                    _token: csrfToken,
                    // other data if needed
                },
                success: function(response) {
                    // Handle success, e.g., update UI
                    console.log(response);
                    window.location.href = '{{ route("todos.index") }}';
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        });

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