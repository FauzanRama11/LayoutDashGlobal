@extends('layouts.master')

@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <h5>Form Program {{ Auth::user()->name }}</h5><span>This is Optional Notes</span>
  </div>
  <div class="card-body">
    <form class="was-validated" action="{{ route('program_stuin.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row">
      
        <div class="row">
          <!-- Kolom Kiri -->
          <div class="col-md-6">
              <div class="mb-3" style="display: none;">
                  <label class="form-label" for="progAge"></label>
                  <input class="form-control" id="progAge" name="progAge" value="Y">
                  <div class="invalid-feedback"></div>
              </div>
      
              <div class="mb-3">
                  <label class="form-label" for="jenisSelect">Jenis</label>
                  <select class="form-select" id="jenisSelect" name="jenisSelect" required>
                      <option value="Ya">Pelaporan</option>
                      <option value="Tidak">Registrasi</option>
                  </select>
                  <div class="invalid-feedback">Pilih jenis program.</div>
              </div>
      
              <div class="mb-3">
                  <label class="form-label" for="nameProg">Nama Program</label>
                  <input class="form-control" id="nameProg" name="nameProg" placeholder="Nama Program" required>
                  <div class="invalid-feedback">Nama program wajib diisi.</div>
              </div>
      
              <div class="mb-3">
                  <label class="form-label" for="startDate">Tanggal Mulai</label>
                  <input type="date" class="form-control" id="startDate" name="startDate" required>
                  <div class="invalid-feedback">Tanggal mulai wajib diisi.</div>
              </div>
      
              <div class="mb-3">
                  <label class="form-label" for="endDate">Tanggal Berakhir</label>
                  <input type="date" class="form-control" id="endDate" name="endDate" required>
                  <div class="invalid-feedback">Tanggal berakhir wajib diisi.</div>
              </div>
      
              <div class="mb-2">
                  <label class="form-label" for="progCategory">Kategori</label>
                  <select class="js-example-basic-single col-sm-12" id="progCategory" name="progCategory" required>
                    @foreach($category as $item)
                      <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback">Pilih kategori.</div>
                </div>
      
              <div class="mb-3">
                  <label class="form-label" for="hostUnit">Unit Penyelenggara</label>
                  <input class="form-control" id="hostUnit" name="hostUnit" value="Airlangga Global Engagement" readonly>
              </div>
      
              <div class="mb-2">
                  <label class="form-label" for="pic">PIC</label>
                  <select class="js-example-basic-single col-sm-12" id="pic" name="pic">
                  @foreach($dosen as $item)
                      <option value="{{ $item->id }}" >{{ $item->nama }}</option>
                  @endforeach
                  </select>
              </div>
      
              <div class="mb-3">
                  <label class="form-label" for="email">Corresponding Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                  <div class="invalid-feedback">Email wajib diisi.</div>
              </div>
      
              <div class="mb-3">
                  <label class="form-label" for="website">Website</label>
                  <input class="form-control" id="website" name="website" placeholder="Website" required>
                  <div class="invalid-feedback">Website wajib diisi.</div>
              </div>
              <div class="mb-3">
                  <label class="form-label" for="via">Via</label>
                  <select class="form-select" id="via" name="via" required>
                      <option value="Offline">Offline</option>
                      <option value="Online">Online</option>
                      <option value="Hybrid">Hybrid</option>
                  </select>
              </div>
          </div>
      
          <!-- Kolom Kanan -->
          <div class="col-md-6">
              <div class="reg" style="display: none;">
                  <div class="mb-3">
                      <label class="form-label" for="regOpen">Tanggal Registrasi Buka</label>
                      <input type="date" class="form-control" id="regOpen" name="regOpen">
                  </div>
                  <div class="mb-3">
                      <label class="form-label" for="regClose">Tanggal Registrasi Tutup</label>
                      <input type="date" class="form-control" id="regClose" name="regClose">
                  </div>
                  <div class="mb-3">
                      <label class="form-label" for="programDesc">Deskripsi Program</label>
                      <textarea class="form-control" id="programDesc" name="programDesc" placeholder="Deskripsi Program"></textarea>
                  </div>

                    <div class="mb-3">
                        <label class="form-label" for="programLogo">Logo/Poster</label>
                        <input class="form-control" type="file" id="programLogo" name="programLogo" accept=".jpg, .jpeg, .png"
                            onchange="handleFileChange(event, 'previewdiv', 'preview', 240)" required>
                    </div>
                    
                    <div class="mb-3">
                        <div id="previewdiv" class="col-sm-12 p-3 justify-content-center align-items-center d-none">
                            <img id="preview" alt="Photo" class="img-fluid" style="height: 240px; object-fit: cover; display: none;">
                        </div>
                    </div>
              </div>
          </div>
    
        <!-- Tombol Submit -->
        <div class="text-end mt-4">
            <a href="{{ route('stuin_program_age') }}" class="btn btn-info mx-1">Back</a>
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </div>
    
    </form>
  </div>
</div>
@endsection

<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    
    let isFileValid = true; // Menyimpan status validasi file

    function handleFileChange(event, previewDivId, previewImgId, imgHeight = null) {
        validateFileSize(event.target);
        previewImage(event.target, previewDivId, previewImgId, imgHeight);
    }

    function validateFileSize(input) {
        const file = input.files[0];
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'File too large!',
                    text: 'The file size exceeds 2 MB. Please upload a smaller file.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = ""; 
                isFileValid = false;
            } else {
                isFileValid = true;
            }
        }
    }
    function previewImage(input, previewDivId, previewImgId, imgHeight = null) {
        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);

        if (!previewDiv || !previewImg) {
            console.error(`Preview div or image element not found: ${previewDivId}, ${previewImgId}`);
            return;
        }

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    previewImg.style.height = imgHeight ? imgHeight + 'px' : '';

                    // 🔹 Ubah div menjadi `d-flex` dan tambahkan border
                    previewDiv.classList.remove('d-none');
                    previewDiv.classList.add('d-flex', 'border', 'border-3');
                };
                reader.readAsDataURL(file);
            } else {
                Swal.fire({
                    title: 'Invalid File Type!',
                    text: 'Only JPG, JPEG, or PNG files are allowed.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });

                input.value = ''; // Reset input file
                resetPreview(previewDiv, previewImg);
            }
        } else {
            resetPreview(previewDiv, previewImg);
        }
    }

    // 🔹 Fungsi untuk menyembunyikan preview jika file tidak ada
    function resetPreview(previewDiv, previewImg) {
        previewDiv.classList.add('d-none'); 
        previewDiv.classList.remove('d-flex', 'border', 'border-3'); 
        previewImg.style.display = 'none'; 
        previewImg.src = ''; 
    }

</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const jenisSelect = document.getElementById('jenisSelect');
    const regSection = document.querySelector('.reg');
    const regInputs = regSection.querySelectorAll('input, select, textarea'); 
    
    jenisSelect.addEventListener('change', function () {
      if (this.value === 'Tidak') {
        regSection.style.display = 'block';
        regInputs.forEach(input => input.setAttribute('required', '')); 
      } else {
        regSection.style.display = 'none';
        regInputs.forEach(input => input.removeAttribute('required')); 
      }
    });
  });
  </script>

<script>
  let isFileValid = true; 

  document.addEventListener("DOMContentLoaded", function () {
      const fileInputs = document.querySelectorAll("input[type='file']");
      console.log('File validation initialized.');

      fileInputs.forEach(input => {
          input.addEventListener("change", handleFileChange);
      });
  });

  function handleFileChange(event) {
      validateFileSize(event.target); 
      previewImage(event.target); 
  }

  function validateFileSize(input) {
      const file = input.files[0]; 
      if (file) {
          const maxSize = 2 * 1024 * 1024; 
          if (file.size > maxSize) {
              Swal.fire({
                  title: 'File too large!',
                  text: 'The file size exceeds 2 MB. Please upload a smaller file.',
                  icon: 'error',
                  confirmButtonText: 'OK'
              });
              input.value = ""; 
              isFileValid = false; 
          } else {
              isFileValid = true; 
          }
      }
  }

  function previewImage(input) {
      const previewDiv = document.getElementById('previewdiv'); 
      const previewImg = document.getElementById('preview'); 

      if (input.files && input.files[0]) {
          const file = input.files[0]; 
          const reader = new FileReader(); 

          if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
              reader.onload = function (e) {
                  previewImg.src = e.target.result; 
                  previewImg.style.display = 'block'; 
                  previewDiv.style.display = 'flex'; 
              };
              reader.readAsDataURL(file); 
          } else {
              Swal.fire({
                  title: 'Invalid File Type!',
                  text: 'Only JPG, JPEG, or PNG files are allowed.',
                  icon: 'error',
                  confirmButtonText: 'OK'
              });
              input.value = ''; 
              previewDiv.style.display = 'none'; 
          }
      } else {
          previewDiv.style.display = 'none';
          previewImg.style.display = 'none';
      }
  }
</script>
