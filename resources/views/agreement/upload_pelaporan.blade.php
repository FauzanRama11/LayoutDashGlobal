@extends('layouts.master')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <h2>Upload Pelaporan</h2>
    <p>This is Upload Pelaporan.</p>

    <div class="card">
        <div class="card-body">
            <form class="was-validated" id="buktiForm" action="{{ route('bukti.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($data))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label" for="linkPelaporan">Upload Pelaporan</label>
                    @if (isset($data) && !empty($data->link_pelaporan))
                        <div class="mb-2">
                            <a href="{{ route('view_naskah.pdf', basename($data->link_pelaporan)) }}" target="_blank" class="btn btn-primary">
                                View / Download Document
                            </a>
                        </div>
                        <input class="form-control" type="file" name="linkPelaporan" accept=".pdf" onchange="validateFileSize(this)">
                        <div class="mb-2">
                            <small>Current file: {{ basename($data->link_pelaporan) }}</small>
                        </div>
                    @else
                        <input class="form-control" type="file" name="linkPelaporan" accept=".pdf" onchange="validateFileSize(this)" required>
                    @endif
                </div>

                
                <div class="mb-3">
                    <label class="form-label" for="buktiP">Upload Bukti Pelaporan Lapkerma</label>
                    <select class="form-select" id="buktiP" name="buktiP" required>
            
                        <option value="Belum" {{ old('buktiP', isset($data->status_pelaporan_lapkerma) ? $data->status_pelaporan_lapkerma : '') == 'Belum' ? 'selected' : '' }}>Belum</option>
                        <option value="Sudah" {{ old('buktiP', isset($data->status_pelaporan_lapkerma) ? $data->status_pelaporan_lapkerma : '') == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                    </select>
                </div>

                <button class="btn btn-primary" id="submitButton" type="submit">Submit</button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>
<script>
        function validateFileSize(input) {
                const file = input.files[0];
                if (file) {
                    const maxSize = 1 * 1024 * 1024; // 1 MB
                    if (file.size > maxSize) {
                        Swal.fire({
                            title: 'File too large!',
                            text: 'The file size exceeds 1 MB. Please upload a smaller file.',
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
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.getElementById('submitButton');
    const form = document.getElementById('buktiForm');
    let isFileValid = true; // Status validasi file

    function validateFileSize(input) {
        const file = input.files[0];
        if (file) {
            const maxSize = 1 * 1024 * 1024; // 1 MB
            const allowedTypes = ["application/pdf"]; // Hanya PDF
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'File terlalu besar!',
                    text: 'Ukuran file melebihi 1 MB. Harap unggah file yang lebih kecil.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = ""; // Reset input file
                isFileValid = false;
            } else if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    title: 'Format tidak valid!',
                    text: 'Harap unggah file dengan format PDF.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = ""; // Reset input file
                isFileValid = false;
            } else {
                isFileValid = true;
            }
        }
    }

    if (submitButton) {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault(); // Mencegah form langsung submit

            // Cek apakah file diupload
            let fileInput = document.querySelector('input[name="linkPelaporan"]');
            if (fileInput && fileInput.required && !fileInput.files.length) {
                Swal.fire({
                    title: 'File diperlukan!',
                    text: 'Harap unggah file sebelum mengirimkan form.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Cek apakah file valid
            if (!isFileValid) {
                Swal.fire({
                    title: 'File tidak valid!',
                    text: 'Harap unggah file PDF dengan ukuran maksimal 1 MB sebelum mengirim.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // SweetAlert konfirmasi sebelum submit
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan mengirimkan form ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, kirim!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim data melalui AJAX
                    let formData = new FormData(form);
                    
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log(response); // Debugging

                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Data berhasil disimpan.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = response.redirect; // Redirect setelah sukses
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr);
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    }
});

    </script>
@endsection
