@extends('admin.authentication.master')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
@endpush


<section style="background: url('{{ asset('assets/images/Unair.png') }}') no-repeat center center; 
                background-size: cover; 
                min-height: 100vh;">
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card" style="border-radius: 10px;">
                    <div class="login-main">
                        
                        <form id="verification-form" class="theme-form login-form" onsubmit="return handleVerification(event);">
                        <img src="{{ asset('assets/images/UnairHorisontal.png') }}" class="img-fluid" 
                        style="max-width: 100%; height: auto; object-fit: cover; margin-bottom:20px;">
                            <div class="form-group">
                                <label>Enter Verification Code</label>
                                <div class="row">
                                    <div class="col">
                                        <input class="form-control text-center opt-text" type="text" placeholder="_ _ _" maxlength="3" required />
                                    </div>
                                    <div class="col">
                                        <input class="form-control text-center opt-text" type="text" placeholder="_ _" maxlength="2" required />
                                    </div>
                                    <div class="col">
                                        <input class="form-control text-center opt-text" type="text" placeholder="---" maxlength="3" required />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Verify</button>
                            </div>
                        </form>

                        <form id="password-form" class="theme-form login-form" style="display: none;" onsubmit="return handlePassword(event);">
                        <img src="{{ asset('assets/images/UnairHorisontal.png') }}" class="img-fluid" 
                        style="max-width: 100%; height: auto; object-fit: cover; margin-bottom:20px;">
                            <h6>Create Your Password</h6>
                            <div class="form-group">
                                <label>New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                    <input class="form-control" type="password" name="login[password]" required placeholder="*********" />
                                    <div class="show-hide"><span class="show"></span></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Retype Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                    <input class="form-control" type="password" name="login[password_confirmation]" required placeholder="*********" />
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Done</button>
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
<script>
function handleVerification(event) {
    event.preventDefault();

    const codeInputs = document.querySelectorAll('.opt-text');
    const code = Array.from(codeInputs).map(input => input.value).join('');

    const encryptedUsername = '{{ $encryptedUsername }}'; 
    console.log(encryptedUsername)

    fetch(`/verify-code/${encryptedUsername}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => {
        if (response.ok) {
            document.getElementById('verification-form').style.display = 'none';
            document.getElementById('password-form').style.display = 'block';
        } else {
            return response.json().then(data => {
                Swal.fire({
                    icon: 'error',
                    title: 'Verification Failed',
                    text: data.message || 'Please check your code and try again.',
                    confirmButtonText: 'Try Again'
                });
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
function handlePassword(event) {
    event.preventDefault();

    const passwordInput = document.querySelector('input[name="login[password]"]').value;
    const passwordConfirmationInput = document.querySelector('input[name="login[password_confirmation]"]').value;
    const encryptedUsername = '{{ $encryptedUsername }}'; 

    fetch(`/set-password/${encryptedUsername}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            password: passwordInput,
            password_confirmation: passwordConfirmationInput
        })
    })
    .then(response => {
        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Password Set Successfully!',
                text: 'You can now log in with your new password.',
                confirmButtonText: 'Go to Login'
            }).then(() => {
                window.location.href = '/'; // Redirect to the login page
            });
        } else {
            return response.json().then(data => {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Set Password',
                    text: data.errors.password[0] || 'Please try again.',
                    confirmButtonText: 'Try Again'
                });
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endpush
