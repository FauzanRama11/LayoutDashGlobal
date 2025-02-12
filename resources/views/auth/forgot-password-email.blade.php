@extends('admin.authentication.master')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
<style>
    .login-card {
        border-radius: 10px;
        overflow: hidden;
    }

    .input-group, .form-control, .btn {
        border-radius: 8px;
    }
</style>

@endpush


<section style="background: url('{{ asset('assets/images/Unair.png') }}') no-repeat center center; 
                background-size: cover; 
                min-height: 100vh;">
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card">
                    <div class="login-main">
                    <form id="verification-form" class="theme-form login-form">
                        <img src="{{ asset('assets/images/UnairHorisontal.png') }}" class="img-fluid" 
                            style="max-width: 100%; height: auto; object-fit: cover; margin-bottom:20px;">
                        <div class="form-group">
                            <label>Confirm Your Registered Email</label>
                            <div class="row">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="icon-email"></i></span>
                                        <input class="form-control" id="emailConfirm" type="email" name="emailConfirm" required placeholder="Enter Your Registered Mail">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="button" onclick="confirmVerification()">Verify</button>
                        </div>
                    </form>

                        </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('scripts')
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<script>
function confirmVerification() {
    let email = document.getElementById("emailConfirm").value;

    if (!email) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Please enter your registered email!'
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to send the data?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!"
    }).then((result) => {
        if (result.isConfirmed) {
            sendVerificationRequest(email);
        }
    });
}

function sendVerificationRequest(email) {
    fetch("{{ route('check.email')}}", {
      
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ emailConfirm: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message 
            }).then(() => {
                window.location.href = "{{ route('verify.codebase', ['encryptedUsername' => '__USERNAME__']) }}".replace('__USERNAME__', encodeURIComponent(data.encryptedUsername));

            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong. Please try again.'
        });
    });
}
</script>

