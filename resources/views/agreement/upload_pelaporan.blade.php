@extends('layouts.master')

@section('content')
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
            const fileInput = document.querySelector('input[name="linkPelaporan"]');
            let isFileValid = true; // Status validasi file

            if (submitButton) {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Check file validation before opening SweetAlert
            if (!isFileValid) {
                Swal.fire({
                    title: 'Invalid File!',
                    text: 'Please upload a valid PDF file (max 2.5 MB) before submitting.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return; // Stop the process if the file is invalid
            }

            // SweetAlert for form submission confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to submit the form?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Use AJAX to submit the form
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log(response); // Log the response for debugging
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: `Data berhasil ${action === 'update' ? 'diperbaharui' : 'tersimpan'}.`,
                                    icon: 'success',
                                    timer: 4000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = response.redirect; // Redirect after showing success message
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message || 'Tidak dapat memproses data.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr); // Log error response for debugging
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

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: {!! json_encode(session('success')) !!},
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: {!! json_encode(session('error')) !!},
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection
