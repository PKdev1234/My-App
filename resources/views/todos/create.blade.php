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
                    <button class="btn btn-danger" id="logoutBtn">Logout</button>
                </div>
            </div>

            <!-- todo create form -->
            <div class="container">
                
                    <h1>Create New Todo</h1>
            
                    <form id="createTodoForm" action="{{ route('todos.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                    
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                            <span class="error-feedback text-danger" id="titleError"></span>
                        </div>
                    
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                            <span class="error-feedback text-danger" id="descriptionError"></span>
                        </div>
                    
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <span class="error-feedback text-danger" id="imageError"></span>
                        </div>
                    
                        <div class="mb-3">
                            <label for="i_is_marked" class="form-label">Is Marked</label>
                            <select id="i_is_marked" name="i_is_marked" class="form-select">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    
                        <button type="button" id="submitBtn" class="btn btn-primary">Create Todo</button>
                    </form>
               
            </div>
                        <!-- todo create form -->
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
<!-- todo create submit before Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit the form?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmSubmitBtn" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    $('#submitBtn').on('click', function() {
        // Show the modal before submitting the form
        $('#confirmationModal').modal('show');
    });

    // Handle the form submission on confirmation
    $('#confirmSubmitBtn').on('click', function() {
        // Clear previous error messages
        $('.error-feedback').text('');

        // Create a FormData object to handle file uploads
        var formData = new FormData($('#createTodoForm')[0]);

        // Make an AJAX request
        $.ajax({
            type: 'POST',
            url: $('#createTodoForm').attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                // Handle success, e.g., show a success message
                //alert(response.message);
                window.location.href = '{{ route("todos.index") }}';
            },
            error: function(xhr, status, error) {
                // Handle errors and display error messages
                var errors = xhr.responseJSON.errors;

                if (errors) {
                    $.each(errors, function(key, value) {
                        $('#' + key + 'Error').text(value[0]);
                    });
                } else {
                    // Handle other types of errors
                    alert('An error occurred. Please try again.');
                }
            }
        });

        // Close the modal after submission
        $('#confirmationModal').modal('hide');
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
