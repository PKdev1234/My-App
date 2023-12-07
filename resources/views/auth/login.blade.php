
@extends('layouts.app')

@section('content')
<div class="container">
    @auth
    <div class="alert alert-info" role="alert">
        You are already logged in.
    </div>
    @else
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    {{-- login form --}}
                    <form id="loginForm">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control" name="email" required>
                            <span class="error-feedback text-danger" id="emailError"></span>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                            <span class="error-feedback text-danger" id="passwordError"></span>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="loginBtn" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                    {{-- login form --}}
                </div>
            </div>
        </div>
    </div>
    
    @endauth
</div>

<!-- login before submit modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Login Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-success">
                <p>Thank you for Login!</p>
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
        $('#loginBtn').on('click', function() {
            // Clear previous error messages
            $('.error-feedback').text('');

            // Serialize the form data
            var formData = $('#loginForm').serialize();

            // Make an AJAX request
            $.ajax({
                type: 'POST',
                url: '{{ route("login") }}',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#successModal').modal('show');

                    // Redirect after the modal is closed, if needed
                    $('#successModal').on('hidden.bs.modal', function () {
                        window.location.href = '/dashboard';
                    });
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
        });
    });
</script>
@endsection
