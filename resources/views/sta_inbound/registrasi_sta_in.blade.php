@extends('pendaftaran.master')

@section('content')

<div class="page-body px-4 py-5">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- Logo Section -->
            <div class="col-lg-4 d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/LogoUnair.png') }}" alt="Preview" class="img-fluid" style="max-width: 80%; height: auto; object-fit: cover;">
            </div>

            <!-- Registration Form Section -->
            <div class="col-lg-8">
                <div class="card shadow-lg p-4">
                    <div class="card-header text-center">
                    <h4 class="fw-bold">Staff Inbound Registration Form</h4>
                        <p class="text-muted">Lorem ipsum this is the desciption of general staff inbound program.</p>
                    </div>

                    <div class="card-body">
                        <form id="regForm" action="" method="POST" enctype="multipart/form-data" onsubmit="return confirmSubmission(event)">
                            @csrf

                            <!-- Step 1: Instructions -->
                            <div id="step-1" class="setup-content">
                                <h4 class="fw-bold">Instructions</h4>
                                <ol class="mb-3">
                                    <li>Please complete this application form in the required format.</li>
                                    <li>Indicate "N/A" if an item is not applicable.</li>
                                    <li>Ensure that all provided information is accurate and truthful.</li>
                                </ol>
                                <p class="mb-3">
                                    For any inquiries, please contact us via email at:
                                    <br>
                                    <a href="mailto:@global.unair.co.id" class="text-primary">
                                        <i class="fa fa-envelope me-2"></i>@global.unair.co.id
                                    </a>
                                </p>
                            </div>

                            <!-- Step 2: Personal Information -->
                            <div id="step-2" class="setup-content">
                                <h4 class="fw-bold">Personal Information</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="nama">Full Name</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter your full name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="prodi_asal">Department</label>
                                        <input type="text" class="form-control" id="prodi_asal" name="prodi_asal" placeholder="e.g., Technology and Science" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="fakultas_asal">Study Program</label>
                                        <input type="text" class="form-control" id="fakultas_asal" name="fakultas_asal" placeholder="e.g., Biotechnology" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="univ">Host University</label>
                                        <select class="form-select" id="univ" name="univ" required>
                                            @foreach($univ as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="kebangsaan">Country</label>
                                        <select class="form-select" id="kebangsaan" name="kebangsaan" required>
                                            @foreach($country as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="text-end mt-4">
                                <button class="btn btn-primary" id="nextBtn" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmSubmission(event) {
    event.preventDefault(); 
    Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to submit the form?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Submit',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
}
</script>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
