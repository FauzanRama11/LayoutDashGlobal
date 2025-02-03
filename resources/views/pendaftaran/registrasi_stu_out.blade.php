@extends('pendaftaran.master')

@section('content') 


<div class="page-body px-4 py-5">
<div class="container-fluid">
    <div class="row">

      <div class="col-md-5 d-flex justify-content-center align-items-center" >
        @if($program->logo_base64)
            <img src="{{ $program->logo_base64 }}" alt="Preview" class="img-fluid" style="max-width:100%; height: 300px; object-fit: cover;">
        @else
            <p>Logo tidak tersedia.</p>
        @endif
      </div>

      <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5>{{ $program->name }}</h5>
                <span>{{ $program->description }}</span>
            </div>
            <div class="card-body">

                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                      <div class="stepwizard-step">
                        <a class="btn btn-primary" href="#step-1">1</a>
                        <p>Step 1</p>
                      </div>
                      <div class="stepwizard-step">
                        <a class="btn btn-light" href="#step-2">2</a>
                        <p>Step 2</p>
                      </div>
                    </div>
                  </div>
              
              <form class="form-wizard" id="regForm" action="{{ route('simpan.stuout') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmSubmission(event)">
                @csrf
                
                <!-- Step 1 -->
                <div id="step-1" class="tab setup-content">
                    <input type="hidden" name="step" value="1">

                    <div class="title">INSTRUCTION</div>
                        <ol>
                            <li>This Application Form must be fully completed in the required format.</li>
                            <li>Please indicate "NA" if an item is not applicable.</li>
                            <li>The Application Form consists of three sections:
                                <ul>
                                <li>Personal Information</li>
                                <li>Educational Qualifications</li>
                                <li>Choice of Subjects</li>
                                </ul>
                            </li>
                        </ol>
                        <br>
                        
                        <p>Please upload the required supporting documents along with this application form:</p>
                        <ol>
                            <li>Student Card</li>
                            <li>Most recent passport-size photo (red/blue/white background)</li>
                            <li>Curriculum Vitae (CV)</li>
                        </ol>
                        <br>

                    <div class="title">NOTE</div>
                    <p>
                        This application will only be processed if all required supporting documents are submitted.
                        <br>
                        For any inquiries, please do not hesitate to contact us via email:
                    <br>
                        <a href="mailto:outbound@global.unair.co.id" style="color:blue;">
                            <i class="fa fa-envelope-o" style="margin-right:5px; color: #222; text-decoration:none;"></i>outbound@global.unair.co.id
                        </a>
                    </p>
                    <br>
                </div>
              
                <!-- Step 2 -->
                <div id="step-2" class="tab setup-content">
                    <input type="hidden" name="step" value="2">
                    
                    <input type="hidden" name="program_id" value="{{ $program->id }}">
                    
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label" for="nama">Full Name *</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" required>
                        <div class="invalid-feedback">Fullname field can not be empty.</div>
                    </div>

                       <!-- Telephone Number -->
                       <div class="mb-3">
                        <label class="form-label" for="nim">NIM *</label>
                        <input type="tel" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM" required>
                        <div class="invalid-feedback">Nomor field can not be empty.</div>
                    </div>

                     <!-- Telephone Number -->
                     <div class="mb-3">
                        <label class="form-label" for="telp">Telephone Number *</label>
                        <input type="tel" class="form-control" id="telp" name="telp" placeholder="Masukkan Nomor Telepon" required>
                        <div class="invalid-feedback">Nomor field can not be empty.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Email field can not be empty.</div>
                    </div>

                    <!-- Sex -->
                    <div class="mb-3">
                        <label class="form-label" for="jenis_kelamin">Sex *</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-Laki" selected>Male</option>
                            <option value="Perempuan">Female</option>
                        </select>
                        <div class="invalid-feedback">Sex field can not be empty.</div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-3">
                        <label class="form-label" for="tgl_lahir">Date of Birth *</label>
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                        <div class="invalid-feedback">Date of Birth field can not be empty.</div>
                    </div>
                    
                    {{-- Education Level --}}
                    <div class="mb-3">
                        <label class="form-label" for="jenjang">Education Level</label>
                        <select class="form-select" id="jenjang" name="jenjang">
                          <option value="diploma" selected>Diploma</option>
                          <option value="bachelor">Bachelor</option>
                          <option value="master">Master</option>
                          <option value="doctor">Doctor</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="tujuan_fakultas_unit">Fakultas</label>
                        <select class="js-example-basic-single col-sm-12" id="tujuan_fakultas_unit" name="tujuan_fakultas_unit">
                        @foreach($fakultas as $item)
                                    <option value="{{ $item->id }}" 
                                        {{ old('tujuan_fakultas_unit', isset($data) ? $data->tujuan_fakultas_unit : '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_ind }}
                                    </option>
                        @endforeach
                        </select>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="tujuan_prodi">Prodi</label>
                        <select class="js-example-basic-single col-sm-12" id="tujuan_prodi" name="tujuan_prodi">
                        @foreach($prodi as $item)
                                    <option value="{{ $item->id }}" 
                                        {{ old('tujuan_prodi', isset($data) ? $data->tujuan_prodi : '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->level }} {{ $item->name }}
                                    </option>
                        @endforeach
                        </select>
                        </div>

              
                    <div class="mb-3">
                        <label class="form-label" for="prodi_asal">Host University Study Progam*</label>
                        <input type="text" class="form-control" id="prodi_asal" name="prodi_asal" placeholder="Biotechnology, etc." required>
                        <div class="invalid-feedback">Study Progam wajib diisi.</div>
                    </div>

                   
                    <div class="mb-3">
                        <label class="form-label" for="fakultas_asal">Host University Faculty*</label>
                        <input type="text" class="form-control" id="fakultas_asal" name="fakultas_asal" placeholder="Biotechnology, etc." required>
                        <div class="invalid-feedback">Faculty wajib diisi.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="univ">Host University</label>
                        <select class="form-select js-example-basic-single" id="univ" name="univ" required>
                        @foreach($univ as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>

                    <!-- <div class="mb-3">
                        <label class="form-label" for="negara_asal_univ">Host University's Country*</label>
                        <select class="form-select js-example-basic-single" id="negara_asal_univ" name="negara_asal_univ" required>
                            @foreach($country as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                    </div> -->

                    <div class="mb-3">
                        <label class="form-label" for="kebangsaan">Nationality*</label>
                        <select class="form-select js-example-basic-single" id="kebangsaan" name="kebangsaan" required>
                            @foreach($country as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="photo_url">Formal Photo</label>
                        <input class="form-control" type="file" id="photo_url" name="photo_url"  accept=".jpg, .jpeg, .png"  onchange="validateFileSize(this)" required>
                        <div class="invalid-feedback">Foto wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="cv_url">CV</label>
                        <input class="form-control" type="file" id="cv_url" name="cv_url"  accept=".pdf" onchange="validateFileSize(this)" required>
                        <div class="invalid-feedback">CV wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="student_id_url">Student ID</label>
                        <input class="form-control" type="file" id="student_id_url" name="student_id_url" onchange="validateFileSize(this)" accept=".pdf .jpg, .jpeg, .png"  required>
                        <div class="invalid-feedback">Passport wajib diisi.</div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label" for="program_info">Where did you first get information about this program?</label>
                        <select class="form-select" id="program_info" name="program_info" required>
                            <option value="" selected>Select your source of information?</option>
                            <option value="Airlangga Global Engagement Website">Airlangga Global Engagement Website</option>
                            <option value="UNAIR Newsletter Email">UNAIR Newsletter Email</option>
                            <option value="UNAIR Social Media (Instagram)">UNAIR Social Media (Instagram)</option>
                            <option value="Airlangga Hubs">Airlangga Hubs</option>
                        </select>
                        <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                    </div>             
                </div>
              
                <!-- Navigation Buttons -->
                <div class="text-end btn-mb">
                    <button class="btn btn-secondary" id="prevBtn" type="button" onclick="nextPrev(-1)">Previous</button>
                    <button class="btn btn-primary" id="nextBtn" type="button" onclick="nextPrev(1)">Next</button>
                </div>
              </form>              
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
        function validateFileSize(input) {
                const file = input.files[0];
                if (file) {
                    const maxSize = 1 * 1024 * 1024;
                    if (file.size > maxSize) {
                        Swal.fire({
                            title: 'File too large!',
                            text: 'The file size exceeds 2 MB. Please upload a smaller file.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        input.value = ""; // Clear the input
                        isFileValid = false; // Tandai file tidak valid
                    } else {
                        isFileValid = true; // Tandai file valid
                    }
                }
            }
            document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("regForm").addEventListener("submit", confirmSubmission);
});

function confirmSubmission(event) {
    event.preventDefault(); // Mencegah submit default

    const form = event.target; // Form elemen
    const action = form.action.includes('update') ? 'update' : 'store'; // Tentukan aksi
    const actionMessages = {
        update: 'Are you sure you want to update the data?',
        store: 'Are you sure you want to save the data?'
    };

    Swal.fire({
        title: 'Confirmation',
        text: actionMessages[action],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim form via AJAX
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: `Data has been ${action === 'update' ? 'updated' : 'saved'}.`,
                        icon: 'success',
                        timer: 4000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect; // Redirect setelah sukses
                    });
                } else {
                    Swal.fire({
                        title: 'Failed!',
                        text: data.message || 'Unable to process data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while processing the data.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

</script>
<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

