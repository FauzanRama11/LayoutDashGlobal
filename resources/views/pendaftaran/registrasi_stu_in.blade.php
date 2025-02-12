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
              
              <form class="form-wizard" id="regForm" action="{{ route('simpan.stuin') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Step 1 -->
                <div id="step-1" class="tab setup-content">
                    <input type="hidden" name="step" value="1">

                    <div class="title">INSTRUCTION</div>
                        <ol>
                            <li>This Application Form must be fully completed in the required format.</li>
                            <li>Please indicate "NA" if an item is not applicable.</li>
                        </ol>
                        <br>
                        
                        <p>Please upload the required supporting documents along with this application form:</p>
                        <ol>
                            <li>Most recent passport-size photo (red/blue/white background)</li>
                            <li>Curriculum Vitae (CV)</li>
                            <li>Passport identity page</li>
                        </ol>
                        <br>

                    <div class="title">NOTE</div>
                    <p>
                        This application will only be processed if all required supporting documents are submitted.
                        <br>
                        For any inquiries, please do not hesitate to contact us via email:
                    <br>
                        <a href="mailto:inbound@global.unair.co.id" style="color:blue;">
                            <i class="fa fa-envelope-o" style="margin-right:5px; color: #222; text-decoration:none;"></i>inbound@global.unair.co.id
                        </a>
                    </p>
                    <br>
                </div>
              
                <!-- Step 2 -->
                <div id="step-2" class="tab setup-content">
                    <input type="hidden" name="step" value="2">
                    
                    <input type="hidden" id="kode" name="kode" value="{{$program->url_generate}}">
                    
                    <div class="mb-3">
                        <label class="form-label" for="nama">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" required>
                        <div class="invalid-feedback">Fullname field can not be empty.</div>
                    </div>

                     <div class="mb-3">
                        <label class="form-label" for="telp">Telephone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="telp" name="telp" placeholder="Masukkan Nomor Telepon" required>
                        <div class="invalid-feedback">Nomor field can not be empty.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Email field can not be empty.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="jenis_kelamin">Sex <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-laki" selected>Male</option>
                            <option value="Perempuan">Female</option>
                            <option value="Other">Prefer not to disclose</option>
                        </select>
                        <div class="invalid-feedback">Sex field can not be empty.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="tgl_lahir">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                        <div class="invalid-feedback">Date of Birth field can not be empty.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="jenjang">Education Level <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenjang" name="jenjang">
                          <option value="diploma" selected>Diploma</option>
                          <option value="bachelor">Bachelor</option>
                          <option value="master">Master</option>
                          <option value="doctor">Doctor</option>
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

                    <div class="mb-3">
                        <label class="form-label" for="negara_asal_univ">Host University's Country*</label>
                        <select class="form-select js-example-basic-single" id="negara_asal_univ" name="negara_asal_univ" required>
                            @foreach($country as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="kebangsaan">Nationality*</label>
                        <select class="form-select js-example-basic-single" id="kebangsaan" name="kebangsaan" required>
                            @foreach($country as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                    </div>

                    <!-- UPLOAD FILE PHOTO -->
                    <div class="mb-3">
                        <label class="form-label" for="photo_url">Photo</label>
                        <input 
                            class="form-control"  
                            type="file" 
                            id="photo_url" 
                            name="photo_url" 
                            accept=".jpg, .jpeg, .png" 
                            onchange="handleImageChange(event, 'photoPreviewDiv', 'photoPreview', 240)" 
                            required>
                    </div>

                    <!-- Preview Gambar -->
                    <div class="mb-3">
                        <div class="col-sm-12 p-3 justify-content-center align-items-center d-none" id="photoPreviewDiv">
                            <img id="photoPreview" src="" alt="Photo Preview" class="img-fluid" style="height: 240px; object-fit: cover; display: none;">
                        </div>
                    </div>

                    <!-- UPLOAD FILE CV -->
                    <div class="mb-3">
                        <label class="form-label" for="cv_url">CV</label>
                        <input class="form-control" type="file" id="cv_url" name="cv_url" accept=".pdf" required onchange="handlePDFChange(event, 'cvPDFLink')">
                        <div class="invalid-feedback">CV wajib diisi.</div>
                    </div>

                    <!-- Link untuk PDF -->
                    <div id="cvPDFLink" class="mt-2 mb-3 d-none">
                        <a id="pdfViewer" href="#" target="_blank" class="btn btn-primary">View / Download CV</a>
                    </div>


                    <div class="mb-3">
                        <label class="form-label" for="selected_id">Selected Identity <span class="text-danger">*</span></label>
                        <select class="form-select" id="selected_id" name="selected_id" required>
                            <option value="">Selected Identity</option>
                            <option value="student_id">Student ID</option>
                            <option value="passport">Passport</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- STUDENT ID FIELD --}}
                    <div class="studentid_field" style="display: none;">

                        <div class="mb-3">
                            <label class="form-label" for="student_no">Student Identity Number</label>
                            <input class="form-control" id="student_no" name="student_no" placeholder="Enter your Student Id Number"  required>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- FILE UPLOAD STUDENT --}}
                        <div class="mb-3">
                            <label class="form-label" for="student_id_url">Student ID</label>
                            <input 
                                class="form-control"  type="file" id="student_id_url" name="student_id_url" accept=".pdf, .jpg, .jpeg, .png" onchange="handleFileChange(event, 'studentIdPreviewDiv', 'studentIdPreview', 'studentIdPDFLink', 240)" 
                                required>
                        </div>
                        
                        <!-- Preview Gambar -->
                        <div class="mb-3">
                            <div 
                                class="col-sm-12 p-3 justify-content-center align-items-center d-none" 
                                id="studentIdPreviewDiv">
                                
                                <img id="studentIdPreview" src="" alt="" class="img-fluid" style="height: 240px; object-fit: cover; display: none;">
                            </div>
                        </div>

                        <!-- Link untuk PDF (Student ID) -->
                        <div id="studentIdPDFLink" class="mt-2 mb-3 d-none">
                            <a id="studentIdPDFViewer" href="#" target="_blank" class="btn btn-primary">View / Download Student ID</a>
                        </div>
                    </div>

                    {{-- PASSPORT FIELD --}}
                    <div class="passport_field" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label" for="passport_no">Paspport Number</label>
                            <input class="form-control" id="passport_no" name="passport_no" placeholder="Nomor Passport" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- FILE UPLOAD PASSPORT --}}
                        <div class="mb-3">
                            <label class="form-label" for="passport_url">Passport Identity Page</label>
                            <input 
                                class="form-control"  
                                type="file" 
                                id="passport_url" 
                                name="passport_url" 
                                accept=".pdf, .jpg, .jpeg, .png"
                                onchange="handleFileChange(event, 'passportPreviewDiv', 'passportPreview', 'passportPDFLink', 240)" 
                                required>
                        </div>
                        
                        <!-- Preview Gambar -->
                        <div class="mb-3">
                            <div class="col-sm-12 p-3 justify-content-center align-items-center d-none" id="passportPreviewDiv">
                                <img id="passportPreview" src="" alt="Preview" class="img-fluid" style="height: 240px; object-fit: cover; display: none;">
                            </div>
                        </div>
                        
                        <!-- Link untuk PDF (Passport) -->
                        <div id="passportPDFLink" class="mt-2 mb-3 d-none">
                            <a id="passportPDFViewer" href="#" target="_blank" class="btn btn-primary">View / Download Passport</a>
                        </div>
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
@endsection


<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- AJAX Verifikasi Email --}}
<script>
    $(document).ready(function() {
        let typingTimer;
        const doneTypingInterval = 2000; // 2 detik delay

        $("#email").on("keyup", function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(checkEmail, doneTypingInterval);
        });

        $("#email").on("keydown", function() {
            clearTimeout(typingTimer);
        });

        function checkEmail() {
            let email = $("#email").val().trim();
            let kode = $("#kode").val().trim();

            if (kode === "") return;

            $.ajax({
                url: "{{ route('stuin.check') }}",
                type: "GET",
                data: { email: email, kode: kode }, 
                success: function(response) {
                    if (response.exists_in_peserta) {
                        Swal.fire({
                            icon: "success",
                            title: "Email telah digunakan!",
                            text: "Email ini telah terdaftar dalam program, hubungi PIC jika ada data yang perlu dirubah.",
                            confirmButtonColor: "#3085d6"
                        });

                        $("#email").val(""); 
                    } else if (response.exists_in_users) {
                        $("#selected_id").prop("disabled", true);
                        $("#passport_no, #passport_url, #student_no, #student_id_url").prop("readonly", true);

                        // Sembunyikan semua form
                        hideForms(document.querySelectorAll('.passport_field'));
                        hideForms(document.querySelectorAll('.studentid_field'));
                    } else {
                        $("#selected_id").prop("disabled", false);
                        $("#passport_no, #passport_url, #student_no, #student_id_url").prop("readonly", false);

                        // Jalankan kembali logika awal form visibility saat onchange
                        updateFormVisibility();
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: xhr.responseJSON?.error || "Terjadi kesalahan saat memeriksa email.",
                        confirmButtonColor: "#d33"
                    });
                }
            });
        }

        // Panggil fungsi updateFormVisibility saat halaman dimuat (jika selected_id telah dipilih)
        updateFormVisibility();

        // Fungsi untuk mengatur form visibility berdasarkan pilihan `selected_id`
        function updateFormVisibility() {
            const selected_program = document.getElementById('selected_id');
            if (!selected_program) return;

            const selectedValue = selected_program.value;

            const passport_field = document.querySelectorAll('.passport_field');
            const studentid_field = document.querySelectorAll('.studentid_field');

            // Sembunyikan semua form
            hideForms(passport_field);
            hideForms(studentid_field);

            // Tampilkan form sesuai dengan pilihan
            if (selectedValue === 'passport') {
                showForms(passport_field);
            } else if (selectedValue === 'student_id') {
                showForms(studentid_field);
            }
        }

        // Event listener saat `selected_id` berubah
        document.getElementById('selected_id').addEventListener('change', updateFormVisibility);

        // Fungsi untuk menyembunyikan semua elemen dalam NodeList
        function hideForms(forms) {
            forms.forEach(form => {
                form.style.display = 'none';
                toggleInputs(form, true);
            });
        }

        // Fungsi untuk menampilkan semua elemen dalam NodeList
        function showForms(forms) {
            forms.forEach(form => {
                form.style.display = 'block';
                toggleInputs(form, false);
            });
        }

        // Fungsi untuk mengaktifkan/menonaktifkan input dalam form
        function toggleInputs(form, isDisabled) {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = isDisabled;

                if (!isDisabled) {
                    if (input.dataset.originalRequired === "true") {
                        input.setAttribute('required', true);
                    }
                } else {
                    if (input.hasAttribute('required')) {
                        input.dataset.originalRequired = "true"; 
                    } else {
                        input.dataset.originalRequired = "false"; 
                    }
                    input.removeAttribute('required');
                }
            });
        }
    });
</script>


{{-- HANYA MENERIMA APPLICATION/IMAGE --}}
<script>
    function handleImageChange(event, previewDivId, previewImgId, imgHeight = null) {
        const input = event.target;
        const file = input.files[0];

        if (!file) {
            resetPreview(previewDivId, previewImgId);
            return;
        }

        // Validasi ukuran file (maksimal 5MB)
        const maxSize = 2 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            Swal.fire({
                title: 'File too large!',
                text: 'The file size exceeds 2 MB. Please upload a smaller image.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            input.value = "";
            resetPreview(previewDivId, previewImgId);
            return;
        }

        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);

        if (!previewDiv || !previewImg) {
            console.error(`Preview elements not found: ${previewDivId}, ${previewImgId}`);
            return;
        }

        if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                previewImg.style.height = imgHeight ? imgHeight + 'px' : '';

                previewDiv.classList.remove('d-none');
                previewDiv.classList.add('d-flex', 'border', 'border-3');
            };
            reader.readAsDataURL(file);
        } else {
            Swal.fire({
                title: 'Invalid File Type!',
                text: 'Only JPG, JPEG, or PNG files are allowed for photos.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            input.value = ''; 
            resetPreview(previewDiv, previewImg);
        }
    }

    function handlePDFChange(event, pdfLinkId) {
        const input = event.target;
        const file = input.files[0];

        if (!file) {
            resetPDFPreview(pdfLinkId);
            return;
        }

        // Validasi ukuran file (maksimal 5MB)
        const maxSize = 2 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            Swal.fire({
                title: 'File too large!',
                text: 'The file size exceeds 2 MB. Please upload a smaller PDF.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            input.value = "";
            resetPDFPreview(pdfLinkId);
            return;
        }

        const pdfLink = document.getElementById(pdfLinkId);
        const pdfViewer = document.getElementById("pdfViewer");

        if (!pdfLink || !pdfViewer) {
            console.error(`Preview elements not found: ${pdfLinkId}`);
            return;
        }

        if (file.type === 'application/pdf') {
            pdfLink.classList.remove('d-none');
            pdfViewer.href = URL.createObjectURL(file);
        } else {
            Swal.fire({
                title: 'Invalid File Type!',
                text: 'Only PDF files are allowed for CVs.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            input.value = ''; 
            resetPDFPreview(pdfLinkId);
        }
    }

    function resetPreview(previewDivId, previewImgId) {
        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);

        if (previewDiv && previewImg) {
            previewDiv.classList.add('d-none'); 
            previewDiv.classList.remove('d-flex', 'border', 'border-3'); 
            previewImg.style.display = 'none'; 
            previewImg.src = ''; 
        }
    }

    function resetPDFPreview(pdfLinkId) {
        const pdfLink = document.getElementById(pdfLinkId);
        const pdfViewer = document.getElementById("pdfViewer");

        if (pdfLink && pdfViewer) {
            pdfLink.classList.add('d-none');
            pdfViewer.href = "#";
        }
    }

</script>

{{-- MENERIMA APPLICATION DAN IMAGE --}}
<script>
    function handleFileChange(event, previewDivId, previewImgId, pdfLinkId, imgHeight = null) {
        const input = event.target;
        const file = input.files[0];

        if (!file) {
            resetAllPreview(previewDivId, previewImgId, pdfLinkId);
            return;
        }

        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            Swal.fire({
                title: 'File too large!',
                text: 'The file size exceeds 5 MB. Please upload a smaller file.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            input.value = "";
            resetAllPreview(previewDivId, previewImgId, pdfLinkId);
            return;
        }

        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);
        const pdfLink = document.getElementById(pdfLinkId);
        const pdfViewer = document.getElementById(pdfLinkId.replace("Link", "Viewer")); // ID Unik

        if (!previewDiv || !previewImg || !pdfLink || !pdfViewer) {
            console.error(`Preview elements not found: ${previewDivId}, ${previewImgId}, ${pdfLinkId}`);
            return;
        }

        resetAllPreview(previewDivId, previewImgId, pdfLinkId);

        const fileURL = URL.createObjectURL(file);

        if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                previewImg.style.height = imgHeight ? imgHeight + 'px' : '';

                previewDiv.classList.remove('d-none');
                previewDiv.classList.add('d-flex', 'border', 'border-3');

                pdfLink.classList.add('d-none'); // Sembunyikan PDF jika file adalah gambar
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            pdfLink.classList.remove('d-none');
            pdfViewer.href = fileURL;
            pdfViewer.target = "_blank"; // Pastikan file bisa dibuka

            previewDiv.classList.add('d-none'); // Sembunyikan preview gambar
            previewImg.style.display = 'none';
        } else {
            Swal.fire({
                title: 'Invalid File Type!',
                text: 'Only JPG, JPEG, PNG, or PDF files are allowed.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            input.value = ''; 
            resetAllPreview(previewDivId, previewImgId, pdfLinkId);
        }
    }

    function resetAllPreview(previewDivId, previewImgId, pdfLinkId) {
        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);
        const pdfLink = document.getElementById(pdfLinkId);
        const pdfViewer = document.getElementById(pdfLinkId.replace("Link", "Viewer"));

        if (previewDiv && previewImg) {
            previewDiv.classList.add('d-none');
            previewDiv.classList.remove('d-flex', 'border', 'border-3');
            previewImg.style.display = 'none';
            previewImg.src = '';
        }

        if (pdfLink && pdfViewer) {
            pdfLink.classList.add('d-none');
            pdfViewer.href = "#";
        }
    }
</script>

{{-- SWEET ALERT FUNCTION --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('file_errors'))
            let errorMessages = "";
            @foreach(session('file_errors') as $error)
                errorMessages += "• {{ $error }}\n";
            @endforeach
            Swal.fire({
                title: 'Validation Failed!',
                text: errorMessages,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                timer: 4000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>

