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
        <!-- Kolom Kiri -->
        <div class="col-md-6">
            <div class="mb-3" style="display: none;">
                <label class="form-label" for="progAge"></label>
                <input class="form-control" id="progAge" name="progAge" value="N">
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
                <input class="form-control" id="hostUnit" name="hostUnit" value="{{ Auth::user()->name }}" readonly>
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
                <label class="form-label" for="via">Type</label>
                <select class="form-select" id="via" name="via" required>
                    <option value="Offline">PT (Part Time)</option>
                    <option value="Online">FT (Full Time)</option>
                </select>
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
                    <input class="form-control" type="file" id="programLogo" name="programLogo" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                </div>
               
                <div class="mt-3">
                    <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="previewdiv" style="display: none;">
                        <img id="preview" src="" alt="Preview" class="img-fluid" style="width: 300px; height: 300px; object-fit: cover; display: none;">
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Tombol Submit -->
        <div class="text-end mt-4">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </div>
    
    </form>
  </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
  const jenisSelect = document.getElementById('jenisSelect');
  const regSection = document.querySelector('.reg');
  const regInputs = regSection.querySelectorAll('input, select, textarea'); 
  
  if (jenisSelect.value === 'Tidak') {
    regSection.style.display = 'block';
    regInputs.forEach(input => input.setAttribute('required', ''));
  } else {
    regSection.style.display = 'none';
    regInputs.forEach(input => input.removeAttribute('required'));
  }
  
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
    function previewImage(event) {
        const input = event.target; // Input elemen file
        const previewDiv = document.getElementById('previewdiv'); // Div container untuk preview
        const previewImg = document.getElementById('preview'); // Elemen img untuk menampilkan gambar

        if (input.files && input.files[0]) {
            const file = input.files[0]; // File yang diunggah
            const reader = new FileReader(); // FileReader untuk membaca file

            // Hanya menampilkan file dengan tipe yang valid
            if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                reader.onload = function (e) {
                    previewImg.src = e.target.result; // Isi src gambar
                    previewImg.style.display = 'block'; // Tampilkan elemen img
                    previewDiv.style.display = 'flex'; // Tampilkan div container
                };
                reader.readAsDataURL(file); // Membaca file sebagai data URL
            } else {
                alert('File yang diunggah harus berupa gambar (JPG, JPEG, atau PNG).');
                input.value = ''; // Reset input file
                previewDiv.style.display = 'none'; // Sembunyikan container
            }
        } else {
            // Sembunyikan preview jika tidak ada file
            previewDiv.style.display = 'none';
            previewImg.style.display = 'none';
        }
    }
</script>