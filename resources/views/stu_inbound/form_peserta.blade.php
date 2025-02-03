@extends('layouts.master') 
@section('content') 

<div class="card">
  <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <div>
          <h5>Form Peserta</h5>
          <span>This is Optional Notes</span>
      </div>

      @if ($data)
        <div class="d-flex">

            <a href="" class="btn btn-info mx-1">Back</a>

            @if ($data->loa_url)
                @if ($data->is_approved == 1)
                    @role('gmp')
                        <button type="button" id="unapproveButton" class="btn btn-primary mx-1">Unapprove</button>
                    @endrole
                    <button type="button" id="bantuanButton" class="btn btn-secondary mx-1">
                        Ajukan Bantuan Dana
                    </button>
                @else
                    @role('fakultas')
                        @if ($data->program->is_private_event === 'Tidak')
                          <form action="{{ route('stuin.approve', $data->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary edit-button">Approve</button>
                          </form>
                        @endif
                    @endrole
                @endif
            @endif

            <button type="button" id="rejectButton1" class="btn btn-danger mx-1">Reject</button>
        </div>
      @endif
  </div>

  <hr>

  <form class="was-validated" action="{{ $data ? route('stuin_peserta.update') : route('stuin_peserta.store') }}"  method="post" enctype="multipart/form-data">
    @csrf
    @if ($data)
        @method('PUT')
        <input type="hidden" name="peserta_id" value="{{ $data->id }}">
    @endif

    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <div>
          <h6>Umum</h6>
          <span>Informasi Umum Peserta</span>
      </div>

      @if ($data)
        <div class="d-flex">
          @if ($data->revision_note && $data->is_approved === 0)
            <button type="button" id="rejectButton1" class="btn btn-warning mx-1">{{ $data->revision_note }}</button>
          @endif
        </div>
      @endif
    </div>

    <div class="card-body">
      <input type="hidden" id="progId" name="progId" value="{{$prog_id}}">

      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="namePeserta">Nama</label>
            <input class="form-control" id="namePeserta" name="namePeserta" placeholder="Nama Peserta"
            value="{{ old('namePeserta', $data->nama ?? '') }}" required>
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="jkPeserta">Jenis Kelamin</label>
            <select class="form-select" id="jkPeserta" name="jkPeserta">
              <option value="Laki-Laki" {{ old('jkPeserta', $data->jenis_kelamin ?? '') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
              <option value="Perempuan" {{ old('jkPeserta', $data->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="dobPeserta">Tanggal Lahir</label>
            <input type="date" class="form-control" id="dobPeserta" name="dobPeserta" required
            value="{{ old('dobPeserta', $data->tgl_lahir ?? '') }}">
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="telpPeserta">No. Telepon</label>
            <input class="form-control" id="telpPeserta" name="telpPeserta" placeholder="Nomor Telepon" required
            value="{{ old('telp', $data->telp ?? '') }}">
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="emailPeserta">Email</label>
            <input type="email" class="form-control" id="emailPeserta" name="emailPeserta" placeholder="Email" required
            value="{{ old('emailPeserta', $data->email ?? '') }}">
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="mb-3">
            <label class="form-label" for="kebangsaan">Kebangsaan</label>
            <select class="form-select js-example-basic-single" id="kebangsaan" name="kebangsaan">
              @foreach($country as $item)
                <option value="{{ $item->id }}" {{ old('kebangsaan', $data->kebangsaan ?? '') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
              @endforeach
            </select>
            <div class="invalid-feedback"></div>
          </div>

          
          <div class="mb-3">
            <label class="form-label" for="jenjangPeserta">Jenjang</label>
            <select class="form-select" id="jenjangPeserta" name="jenjangPeserta">
              <option value="diploma" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'diploma' ? 'selected' : '' }}>Diploma</option>
              <option value="bachelor" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
              <option value="master" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'master' ? 'selected' : '' }}>Master</option>
              <option value="doctor" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'doctor' ? 'selected' : '' }}>Doctor</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="prodi_asal">Prodi Asal</label>
            <input class="form-control" id="prodi_asal" name="prodi_asal" placeholder="Prodi Tujuan" required
            value="{{ old('prodi_asal', $data->prodi_asal ?? '') }}">
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="fakultas_asal">Fakultas Asal</label>
            <input class="form-control" id="fakultas_asal" name="fakultas_asal" placeholder="Fakultas Asal" required
            value="{{ old('fakultas_asal', $data->fakultas_asal ?? '') }}">
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">

          <div class="mb-3">
            <label class="form-label" for="univ_asal">Universitas</label>
            <select class="form-select js-example-basic-single" id="univ_asal" name="univ_asal">
              @foreach($univ as $item)
                    <option value="{{ $item->id }}" {{ old('univ_asal', $data->univ ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                      </option>
                @endforeach
            </select>
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="negara_asal">Negara Asal Univ</label>
            <select class="form-select js-example-basic-single" id="negara_asal" name="negara_asal">
                @foreach($country as $item)
                    <option value="{{ $item->id }}" {{ old('negara_asal', $data->negara_asal_univ ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                      </option>
                @endforeach
            </select>
            <div class="invalid-feedback"></div>
          </div>
          
          {{-- Photo URL --}}

          @if(isset($data) && !empty($data->photo_url))
              <div class="mb-3">
                  <label class="form-label" for="fotoPeserta">Photo</label>
                  <input class="form-control" type="file" id="fotoPeserta" name="fotoPeserta" accept=".jpg, .jpeg, .png"
                      onchange="previewImage(event, 'photoPreviewDiv', 'photoPreview', 240)">
                  <div class="invalid-feedback"></div>
              </div>
          @else
              <div class="mb-3">
                  <label class="form-label" for="fotoPeserta">Photo</label>
                  <input class="form-control" type="file" id="fotoPeserta" name="fotoPeserta" accept=".jpg, .jpeg, .png"
                      onchange="previewImage(event, 'photoPreviewDiv', 'photoPreview', 240)" required>
                  <div class="invalid-feedback"></div>
              </div>
          @endif

          <div class="mb-3">
            <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="photoPreviewDiv"sty>
              <img 
                id="photoPreview" 
                src="{{ isset($data) && !empty($data->photo_base64) ? $data->photo_base64 : '' }}" 
                alt="{{ isset($data) && !empty($data->photo_base64) ? 'Photo Preview' : '' }}" 
                class="img-fluid" 
                style="{{ isset($data) && !empty($data->photo_base64) ? 'height: 240px; object-fit: cover;' : '' }}">
            </div>
          </div>
          

          {{-- CV --}}
          <div class="mb-3">
            <label class="form-label" for="cvPeserta">CV</label>
              @if (isset($data) && !empty($data->cv_url))
              <input class="form-control" type="file" id="cvPeserta " name="cvPeserta" accept=".pdf">
                  <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                  @error('url_attachment')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  
                  <div class="mt-2">
                    @php
                        $filePath = ltrim(str_replace('repo/', '', $data->cv_url), '/');
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        
                        $encodedFileName = urlencode($fileName);
                        $encodedFolder = urlencode($folder);
                    @endphp
        
                    <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                        View / Download Passport
                    </a>
                </div>
              @else
                  <!-- Input file jika ID tidak ada -->
                  <input class="form-control @error('cvPeserta') is-invalid @enderror" type="file" name="cvPeserta" required>
                  @error('cvPeserta')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian Approval -->
    <div class="card-header pb-0">
      <h6>Approval</h6>
      <span>Approval Peserta</span>
    </div>
    
    <div class="card-body">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="loaPeserta">LoA</label>
              @if (isset($data) && !empty($data->loa_url))
                  <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf">
                  <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                  @error('url_attachment')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  
                  <div class="mt-2">
                    @php
                        $filePath = ltrim(str_replace('repo/', '', $data->loa_url), '/');
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        
                        $encodedFileName = urlencode($fileName);
                        $encodedFolder = urlencode($folder);
                    @endphp
        
                    <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                        View / Download Passport
                    </a>
                </div>
              @else
                  <!-- Input file jika ID tidak ada -->
                  <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf" required>
                  <div class="form-text"></div>
                  @error('url_attachment')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              @endif
          </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">

          @if (Auth::user()->hasRole('fakultas')) 
          <div class="mb-3">
              <label class="form-label" for="tfakultasPeserta">Fakultas Tujuan</label>
              <input class="form-control"  id="tfakultasPeserta" name="tfakultasPeserta" value="{{ Auth::user()->name }}" readonly>
          </div>
          @else
            <div class="mb-3">
                <label class="form-label" for="tfakultasPeserta">Fakultas Tujuan</label>
                <input class="form-control"  id="tfakultasPeserta" name="tfakultasPeserta"  value="{{  $data->tujuan_fakultas_unit ?? '' }}"  readonly>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label" for="tprodiPeserta">Prodi Tujuan</label>
            <select class="form-select js-example-basic-single" id="tprodiPeserta" name="tprodiPeserta">
                @foreach($prodi as $item)
                    <option value="{{ $item->id }}" 
                        {{ old('tprodiPeserta', isset($data) ? $data->tujuan_prodi : '') == $item->id ? 'selected' : '' }}>
                        {{ $item->level }} {{ $item->name }}
                    </option>
                @endforeach
            </select>
          </div>
        
        </div>
      </div>
    </div>

    <!-- Bagian Imigrasi -->
    <div class="card-header pb-0">
      <h6>Imigrasi</h6>
      <span>Imigrasi Peserta</span>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="noPassPeserta">Nomor Passport</label>
            <input class="form-control" id="noPassPeserta" name="noPassPeserta" placeholder="Nomor Passport"
            value="{{ old('noPassPeserta', $data->passport_no ?? '') }}">
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="homePeserta">Home Address</label>
            <textarea class="form-control" id="homePeserta" name="homePeserta" placeholder="Home Address">{{ old('homePeserta', $data->home_address ?? '') }}</textarea>
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="passPeserta">Passport</label>
              @if (isset($data) && !empty($data->passport_url))
                  <input class="form-control" type="file" id="passPeserta" name="passPeserta" accept=".pdf">
                  <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                  @error('passPeserta')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
          
                  <div class="mt-2">
                    @php
                        $filePath = ltrim(str_replace('repo/', '', $data->passport_url), '/');
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        
                        $encodedFileName = urlencode($fileName);
                        $encodedFolder = urlencode($folder);
                    @endphp
        
                    <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                        View / Download Passport
                    </a>
                  </div>
              @else
                  <!-- Input file jika Passport belum ada -->
                  <input class="form-control @error('passPeserta') is-invalid @enderror" type="file" name="passPeserta" required>
                  @error('passPeserta')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              @endif
          </div>
        
          <div class="mb-3">
            <label class="form-label" for="idPeserta">Student ID</label>
            <input class="form-control" type="file" id="idPeserta" name="idPeserta" accept=".jpg, .jpeg, .png" onchange="previewImage(event, 'studentIDPreviewDiv', 'studentIDPreview', 240)">
          </div>
          
          <div class="mb-3">
            <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" 
                 id="studentIDPreviewDiv"
                 style="display: {{ isset($data) && !empty($data->id_base64) ? 'block' : 'none' }};">
                <img 
                    id="studentIDPreview" 
                    src="{{ isset($data) && !empty($data->id_base64) ? $data->id_base64 : '' }}" 
                    alt="{{ isset($data) && !empty($data->id_base64) ? 'Student ID Preview' : '' }}" 
                    class="img-fluid" 
                    style="{{ isset($data) && !empty($data->id_base64) ? 'height: 240px; object-fit: cover;' : '' }}">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tombol Submit -->
    <div class="card-footer text-end">
      <button class="btn btn-primary" type="submit">Submit</button>
    </div>
  </form>
</div>


@endsection

<script>
  document.addEventListener("DOMContentLoaded", function() {
      var inputs = document.querySelectorAll("input[required], select[required], textarea[required]");
  
      inputs.forEach(input => {
          input.addEventListener("input", function() {
              if (this.value.trim() === "") {
                  this.classList.add("is-invalid"); 
                  this.classList.remove("is-valid");
              } else {
                  this.classList.add("is-valid");
                  this.classList.remove("is-invalid");
              }
          });
      });
  });
  
  function validateForm(event) {
      var inputs = document.querySelectorAll("input[required], select[required], textarea[required]");
      var valid = true;
  
      inputs.forEach(input => {
          if (input.value.trim() === "") {
              input.classList.add("is-invalid"); 
              input.classList.remove("is-valid");
              valid = false;
          }
      });
  
      if (!valid) {
          alert("Harap isi semua field yang wajib!");
          event.preventDefault();
      }
  }
  
  function previewImage(event, previewDivId, previewImgId, imgHeight = null) {
      const input = event.target;
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
                  if (imgHeight) previewImg.style.height = imgHeight + 'px';
                  previewImg.style.display = 'block';
                  previewDiv.style.display = 'flex';
              };
              reader.readAsDataURL(file);
          } else {
              alert('File harus berupa gambar (JPG, JPEG, atau PNG).');
              input.value = '';
              previewDiv.style.display = 'none';
          }
      } else {
          previewDiv.style.display = 'none';
          previewImg.style.display = 'none';
          previewImg.src = '';
      }
  }
  </script>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Pastikan jQuery tersedia -->


  <script>
    $(document).ready(function () {
        $('#bantuanButton').click(function () {
            Swal.fire({
                title: 'Ajukan Bantuan Dana',
                input: 'select',
                inputOptions: {
                    'RKAT': 'RKAT',
                    'DPAT': 'DPAT'
                },
                inputPlaceholder: 'Pilih Sumber Dana',
                showCancelButton: true,
                confirmButtonText: 'Ajukan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Anda harus memilih salah satu!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let tipe = result.value; // Ambil nilai yang dipilih
                    let id = "{{ $data->id ?? '' }}"; // Ambil ID dari Blade
                    
                    // Kirim AJAX ke Laravel
                    $.ajax({
                        url: "{{ route('ajukan.bantuan.dana') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            tipe: tipe
                        },
                        success: function (response) {
                            Swal.fire('Berhasil!', response.message, 'success')
                            .then(() => {
                                location.reload(); // Refresh halaman setelah sukses
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Error!', 'Terjadi kesalahan, coba lagi.', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>