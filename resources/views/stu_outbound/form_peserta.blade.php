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
            @if ($data->program->is_program_age == "N")
            @hasanyrole('fakultas|gmp')
                @if($data->program->is_private_event == "Tidak" && $data->loa_url)
                  <form action="{{ route('stuout.approve', $data->id) }}" method="POST">
                                          @csrf
                                          <button type="submit" class="btn btn-primary edit-button">Approve</button>
                                      </form>
                              <form action="{{ route('stuout.reject', $data->id) }}" method="POST">
                                @csrf
                                <button type="submit" id="rejectButton1" class="btn btn-danger mx-1">Reject</button>
                              </form>
                @endif
                
                @if ($data->is_approved == 1)
                  <button type="button" id="unapproveButton" class="btn btn-warning mx-1">Unapprove</button>
                  @if ($data->pengajuan_dana_status != "APPROVED")
                        <button type="button" id="bantuanButton" class="btn btn-secondary mx-1">
                          Ajukan Bantuan Dana
                        </button>
                  @endif
                @endif
                @endhasanyrole
            @else
              @hasrole('gmp')
                @if($data->program->is_private_event == "Tidak" && $data->loa_url)
                  <form action="{{ route('stuout.approve', $data->id) }}" method="POST">
                                          @csrf
                                          <button type="submit" class="btn btn-primary edit-button">Approve</button>
                                      </form>
                              <form action="{{ route('stuout.reject', $data->id) }}" method="POST">
                                @csrf
                                <button type="submit" id="rejectButton1" class="btn btn-danger mx-1">Reject</button>
                              </form>
                @endif
                @if ($data->is_approved == 1)
                  <button type="button" id="unapproveButton" class="btn btn-warning mx-1">Unapprove</button>
                  @if ($data->pengajuan_dana_status != "APPROVED")
                                    <button type="button" id="bantuanButton" class="btn btn-secondary mx-1">
                                        Ajukan Bantuan Dana
                                    </button>
                                  @endif
                @endif
              @endhasrole
		      @endif
         </div>
        @endif
  </div>

  <hr>

    <form class="was-validated" action="{{ $data ? route('stuout_peserta.update') : route('stuout_peserta.store') }}" method="post" enctype="multipart/form-data">
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
    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
        <div class="mb-3" style="display: none;">
            <label class="form-label" for="progId"></label>
            <input class="form-control" id="progId" name="progId" value="{{$prog_id}}" readonly>
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="namePeserta">Nama</label>
          <input class="form-control" id="namePeserta" name="namePeserta" placeholder="Nama Peserta"
          value="{{ old('namePeserta', $data->nama ?? '') }}" required>
          <div class="invalid-feedback">Nama wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="nimPeserta">NIM</label>
          <input class="form-control" id="nimPeserta" name="nimPeserta" placeholder="NIM Peserta" value="{{ old('nimPeserta', $data->nim ?? '') }}" required>
          <div class="invalid-feedback">NIM wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="angkatanPeserta">Angkatan</label>
          <select class="form-select" id="angkatanPeserta" name="angkatanPeserta" required>
            <option value="2021" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2021' ? 'selected' : '' }}>2021</option>
            <option value="2022" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2022' ? 'selected' : '' }}>2022</option>
            <option value="2023" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2023' ? 'selected' : '' }}>2023</option>
            <option value="2024" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2024' ? 'selected' : '' }}>2024</option>
            <option value="2025" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2025' ? 'selected' : '' }}>2025</option>
            <option value="2026" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2026' ? 'selected' : '' }}>2026</option>
            <option value="2027" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2027' ? 'selected' : '' }}>2027</option>
            <option value="2028" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2028' ? 'selected' : '' }}>2028</option>
            <option value="2029" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2029' ? 'selected' : '' }}>2029</option>
            <option value="2030" {{ old('angkatanPeserta', $data->angkatan ?? '') == '2030' ? 'selected' : '' }}>2030</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label" for="jkPeserta">Jenis Kelamin</label>
          <select class="form-select" id="jkPeserta" name="jkPeserta">
            <option value="Laki-Laki" {{ old('jkPeserta', $data->jenis_kelamin ?? '') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="Perempuan" {{ old('jkPeserta', $data->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
          </select>
        </div>
          
        <div class="mb-3">
          <label class="form-label" for="dobPeserta">Tanggal Lahir</label>
          <input type="date" class="form-control" id="dobPeserta" name="dobPeserta" required
          value="{{ old('dobPeserta', $data->tgl_lahir ?? '') }}">
          <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="telpPeserta">No. Telepon</label>
          <input class="form-control" id="telpPeserta" name="telpPeserta" placeholder="Nomor Telepon" required
          value="{{ old('telp', $data->telp ?? '') }}">
          <div class="invalid-feedback">Nomor Telepon wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="emailPeserta">Email</label>
          <input type="email" class="form-control" id="emailPeserta" name="emailPeserta" placeholder="Email" required
          value="{{ old('emailPeserta', $data->email ?? '') }}">
          <div class="invalid-feedback">Email wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="jenjangPeserta">Jenjang</label>
          <select class="form-select" id="jenjangPeserta" name="jenjangPeserta">
            <option value="diploma" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'diploma' ? 'selected' : '' }}>Diploma</option>
            <option value="bachelor" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
            <option value="master" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'master' ? 'selected' : '' }}>Master</option>
            <option value="doctor" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'doctor' ? 'selected' : '' }}>Doctor</option>
          </select>
        </div>
        </div>
        <div class="col-md-6">

        <div class="mb-3">
            <label class="form-label" for="tProdiPeserta">Tujuan Prodi</label>
            <input class="form-control" id="tProdiPeserta" name="tProdiPeserta" placeholder="Prodi Tujuan" required
             value="{{ old('tProdiPeserta', $data->prodi_asal ?? '') }}">
            <div class="invalid-feedback">Tujuan prodi wajib diisi.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="tFakultasPeserta">Tujuan Fakultas</label>
            <input class="form-control" id="tFakultasPeserta" name="tFakultasPeserta" placeholder="Fakultas Tujuan" required
            value="{{ old('tFakultasPeserta', $data->fakultas_asal ?? '') }}">
            <div class="invalid-feedback">Tujuan fakultas wajib diisi.</div>
        </div>

        <div class="mb-2">
        <label class="form-label" for="univTujuanPeserta">Universitas Tujuan</label>
        <select class="js-example-basic-single col-sm-12" id="univTujuanPeserta" name="univTujuanPeserta">
        @foreach($univ as $item)
                    <option value="{{ $item->id }}" {{ old('univTujuanPeserta', $data->univ ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                      </option>
                @endforeach
        </select>
        </div>

  
        <!-- <div class="mb-3">
            <label class="form-label" for="fotoPeserta">Foto</label>
            <input class="form-control" type="file" id="fotoPeserta" name="fotoPeserta"  accept=".jpg, .jpeg, .png"  required>
            <div class="invalid-feedback">Foto wajib diisi.</div>
        </div> -->

        
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
          

          <div class="mb-3">
            <label class="form-label" for="cvPeserta">CV</label>
              @if (isset($data) && !empty($data->cv_url))
              <input class="form-control" type="file" id="cvPeserta " name="cvPeserta" accept=".pdf">
                  <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                  <small>Current file: {{ basename($data->cv_url) }}</small>
                  @error('url_attachment')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  
                  <div class="mt-2">
                    @php
                        $filePath = ltrim(str_replace('repo/', '', $data->cv_url), '/');
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        
                        if( $folder === ''){
                          $encodedFileName = urlencode($folder);
                          $encodedFolder = urlencode($fileName);
                        } else {
                          $encodedFileName = urlencode($fileName);
                          $encodedFolder = urlencode($folder);
                        }
                    @endphp
        
                    <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                        View / Download CV
                    </a>
                </div>
              @else
                  <!-- Input file jika ID tidak ada -->
                  <input class="form-control @error('cvPeserta') is-invalid @enderror" type="file" name="cvPeserta" accept=".pdf" required>
                  @error('cvPeserta')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              @endif
          </div>
        </div>



        <div class="card-header pb-0">
          <h5>Approval</h5><span>Approval Peserta</span>
        </div>

      <div class="card-body">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="loaPeserta">LoA</label>
              @if (isset($data) && !empty($data->loa_url))
                  <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf" required>
                  <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                  <small>Current file: {{ basename($data->loa_url) }}</small>
                  @error('url_attachment')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  
                  <div class="mt-2">
                    @php
                        $filePath = ltrim(str_replace('repo/', '', $data->cv_url), '/');
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        
                        if( $folder === ''){
                          $encodedFileName = urlencode($folder);
                          $encodedFolder = urlencode($fileName);
                        } else {
                          $encodedFileName = urlencode($fileName);
                          $encodedFolder = urlencode($folder);
                        }
                    @endphp
        
                    <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                        View / Download LoA
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
        <div class="col-md-6">

          <div class="mb-2">
          <label class="form-label" for="fakultasPeserta">Fakultas</label>
          <select class="js-example-basic-single col-sm-12" id="fakultasPeserta" name="fakultasPeserta">
            @foreach($fakultas as $item)
                      <option value="{{ $item->id }}" 
                          {{ old('fakultasPeserta', isset($data) ? $data->tujuan_fakultas_unit : '') == $item->nama_ind ? 'selected' : '' }}>
                        {{ $item->nama_ind }}
                      </option>
            @endforeach
          </select>
          </div>

          <div class="mb-2">
          <label class="form-label" for="prodiPeserta">Prodi</label>
          <select class="js-example-basic-single col-sm-12" id="prodiPeserta" name="prodiPeserta">
            @foreach($prodi as $item)
                      <option value="{{ $item->id }}" 
                          {{ old('prodiPeserta', isset($data) ? $data->tujuan_prodi : '') == $item->id ? 'selected' : '' }}>
                          {{ $item->level }} {{ $item->name }}
                      </option>
            @endforeach
          </select>
          </div>
        </div>
      </div>
    </div>

        <div class="card-header pb-0">
          <h5>Imigrasi</h5><span>Imigrasi Peserta</span>
        </div>
    
    <div class="card-body">
    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="noPassPeserta">Nomor Passport</label>
            <input class="form-control" id="noPassPeserta" name="noPassPeserta" placeholder="Nomor Passport" value="{{ old('noPassPeserta', $data->passport_no ?? '') }}">
            <div class="invalid-feedback">Nomor passport wajib diisi.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="homePeserta">Home Address</label>
            <textarea class="form-control" id="homePeserta" name="homePeserta" placeholder="Home Address">{{ old('homePeserta', $data->home_address ?? '') }}</textarea>
            <div class="invalid-feedback"></div>
        </div>
        </div>

        <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="passPeserta">Passport</label>
              @if (isset($data) && !empty($data->passport_url))
                  <input class="form-control" type="file" id="passPeserta" name="passPeserta" accept=".pdf">
                  <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                  <small>Current file: {{ basename($data->passport_url) }}</small>
                  @error('passPeserta')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
          
                  <div class="mt-2">
                    @php
                        $filePath = ltrim(str_replace('repo/', '', $data->cv_url), '/');
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        
                        if( $folder === ''){
                          $encodedFileName = urlencode($folder);
                          $encodedFolder = urlencode($fileName);
                        } else {
                          $encodedFileName = urlencode($fileName);
                          $encodedFolder = urlencode($folder);
                        }
                    @endphp
        
                    <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                        View / Download Passport
                    </a>
                  </div>
              @else
                  <!-- Input file jika Passport belum ada -->
                  <input class="form-control @error('passPeserta') is-invalid @enderror" type="file" accept=".pdf" name="passPeserta">
                  @error('passPeserta')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              @endif
        </div>
          </div>
        </div>
    </div>
    </div>
    </div>

 
     <div class="card-footer text-end">
      <button class="btn btn-primary" type="submit">Submit</button>
    </div>
    </form>
    </div>
</div>
<script>
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
@if($data)
  <script>
      document.addEventListener("DOMContentLoaded", function () {
          let participantId = "{{ $data->id ?? '' }}"; 

          function sendRequest(url, method, data, successMessage) {
              $.ajax({
                  url: url,
                  type: method,
                  data: {
                      _token: "{{ csrf_token() }}",
                      id: participantId,
                      ...data 
                  },
                  success: function (response) {
                      Swal.fire('Berhasil!', successMessage, 'success').then(() => {
                          location.reload();
                      });
                  },
                  error: function (xhr) {
                      Swal.fire('Error!', 'Terjadi kesalahan, coba lagi.', 'error');
                  }
              });
          }

          $('#bantuanButton').click(function () {
              Swal.fire({
                  title: 'Ajukan Bantuan Dana',
                  input: 'select',
                  icon: "warning",
                  inputOptions: {
                      'RKAT': 'RKAT',
                      'DAPT': 'DAPT'
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
                      sendRequest("{{ route('ajukan.bantuan.dana.out') }}", "POST", { tipe: result.value }, "Bantuan dana berhasil diajukan.");
                  }
              });
          });

          $('#approveButton').click(function () {
              Swal.fire({
                  title: "Approve Participant?",
                  text: "Once approved, this participant will be officially recognized.",
                  icon: "success",
                  showCancelButton: true,
                  confirmButtonColor: "#28a745",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Yes, Approve!"
              }).then((result) => {
                  if (result.isConfirmed) {
                      sendRequest("{{ route('stuout.approve', $data->id) }}", "POST", {}, "Participant approved successfully!");
                  }
              });
          });

          $('#unapproveButton').click(function () {
              Swal.fire({
                  title: "Unapprove Participant?",
                  text: "This will remove the approval status from this participant.",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#ffc107",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Yes, Unapprove!"
              }).then((result) => {
                  if (result.isConfirmed) {
                      sendRequest("{{ route('stuout.unapprove', $data->id) }}", "POST", {}, "Participant approval has been removed.");
                  }
              });
          });

          $('#rejectButton').click(function () {
              Swal.fire({
                  title: "Reject Participant?",
                  text: "Are you sure you want to reject this participant?",
                  icon: "error",
                  showCancelButton: true,
                  confirmButtonColor: "#dc3545",
                  cancelButtonColor: "#6c757d",
                  confirmButtonText: "Yes, Reject!"
              }).then((result) => {
                  if (result.isConfirmed) {
                      sendRequest("{{ route('stuout.reject', $data->id) }}", "POST", {}, "Participant has been rejected.");
                  }
              });
          });
      });
  </script>
@endif
@endsection
