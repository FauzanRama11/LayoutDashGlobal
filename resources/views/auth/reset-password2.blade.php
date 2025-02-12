@extends("layouts.master")

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <h5>Form Change Password</h5>
        <span>Change your password.</span>
    </div>
    <form class="was-validated" id = "updateForm" action="{{ route('store.change.pass', ['username' => Crypt::encryptString(Auth::user()->username)]) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" id="username" name="username" 
                            value="{{ old('username', $data->username ?? '') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" id="name" name="name" 
                            value="{{ old('name', $data->name ?? '') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="old_password">Old Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                        <div class="invalid-feedback">Old Password is Required.</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="invalid-feedback">New Password is Required.</div>
                    </div>
                    @if ($errors->has('new_password'))
                        <div class="text-danger">{{ $errors->first('new_password') }}</div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">Confirm New Password is Required.</div>
                    </div>

                  

                    
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary" id="confirmUpdate">Change Password</button>
        </div>
    </form>
</div>
@endsection

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('confirmUpdate').addEventListener('click', function (event) {
    event.preventDefault(); // Mencegah eksekusi langsung
    
    if (validateForm()) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update the data?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = event.target.closest('form'); // Ambil form dari event
                let formData = new FormData(form);

                fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        title: data.status === 'success' ? 'Success!' : 'Error!',
                        text: data.message,
                        icon: data.status === 'success' ? 'success' : 'error'
                    }).then(() => {
                        if (data.status === 'success') {
                            window.location.href = data.redirect; // Redirect setelah sukses
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Something went wrong. Please try again.',
                        icon: 'error'
                    });
                });
            }
        });
    }
});


    
function validateForm() {
    let isValid = true;
    let requiredFields = document.querySelectorAll('input[required], textarea[required], select[required]'); // Tangani semua required fields
    let requiredFiles = document.querySelectorAll('input[type="file"][required]'); // Tangani file input yang required

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add("is-invalid");

            let feedback = field.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.textContent = "This field is required.";
                feedback.style.display = "block";
            }
        } else {
            field.classList.remove("is-invalid");

            let feedback = field.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.style.display = "none";
            }
        }
    });

    // Validasi khusus untuk file upload
    requiredFiles.forEach(fileInput => {
        if (fileInput.files.length === 0) {
            isValid = false;
            fileInput.classList.add("is-invalid");

            let feedback = fileInput.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.textContent = "You must upload this file.";
                feedback.style.display = "block";
            }
        } else {
            fileInput.classList.remove("is-invalid");
            let feedback = fileInput.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.style.display = "none";
            }
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields before submitting.',
            confirmButtonColor: '#d33'
        });
    }

    return isValid;
}

    })
</script>