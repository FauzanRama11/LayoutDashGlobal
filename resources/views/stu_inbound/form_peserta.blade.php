@extends('layouts.master') 
@section('content') 

<div class="card">
  <div class="card-header pb-0">
      <div class="row align-items-center">
          <div class="col-md-6 col-12 mb-2">
              <h5 class="mb-1">Form Peserta</h5>
              <span class="text-muted">This is Optional Notes</span>
          </div>

          @if ($data)
          <div class="col-md-6 col-12">
              <div class="d-flex flex-wrap justify-content-md-end justify-content-center gap-2">

                  {{-- Back Button --}}
                  <button type="button" class="btn btn-info" onclick="window.location.href='{{ $data->is_program_age === 'N' ? route('stuin_program_fak') : route('stuin_program_age') }}'">
                      <i class="fa fa-arrow-left"></i> Back
                  </button>

                  {{-- Check if LoA URL exists --}}
                  @if ($data->loa_url)
                      @if ($data->is_approved == 1)
                          @if ($data->program->is_private_event === 'Tidak')
                              @role('fakultas')
                                  {{-- Unapprove Button --}}
                                  <button type="button" id="unapproveButton" class="btn btn-warning">
                                      <i class="fa fa-times-circle"></i> Unapprove
                                  </button>
                              @endrole
                          @endif

                          {{-- Ajukan Bantuan Dana Button --}}
                          @if ($data->pengajuan_dana_status === 'EMPTY')
                            <button type="button" id="bantuanButton" class="btn btn-secondary">
                                <i class="fa fa-hand-holding-usd"></i> Ajukan Bantuan Dana
                            </button>
                          @endif
                      @else
                          @role('fakultas')
                              @if ($data->program->is_private_event === 'Tidak')
                                  {{-- Approve Button --}}
                                  <button type="button" id="approveButton" class="btn btn-success">
                                      <i class="fa fa-check-circle"></i> Approve
                                  </button>

                                  {{-- Reject Button --}}
                                  @if ($data->is_approved === 0)
                                      <button type="button" id="rejectButton" class="btn btn-danger">
                                          <i class="fa fa-ban"></i> Reject
                                      </button>
                                  @endif
                              @endif
                          @endrole
                      @endif
                  @endif
              </div>
          </div>
          @endif
      </div>
  </div>

  <hr>

  <form id="fromPStuIn" onsubmit="return confirmSubmission(event)" class="was-validated" action="{{ $data ? route('stuin_peserta.update') : route('stuin_peserta.store') }}"  method="post" enctype="multipart/form-data">
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
          <button type="button" id="rejectButton1" class="btn btn-warning mx-1">Revision : {{ $data->revision_note }}</button>
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
                      onchange="handleFileChange(event, 'photoPreviewDiv', 'photoPreview', 240)">
                  <div class="invalid-feedback"></div>
              </div>
          @else
              <div class="mb-3">
                  <label class="form-label" for="fotoPeserta">Photo</label>
                  <input class="form-control" type="file" id="fotoPeserta" name="fotoPeserta" accept=".jpg, .jpeg, .png"
                      onchange="handleFileChange(event, 'photoPreviewDiv', 'photoPreview', 240)" required>
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
              <input class="form-control" type="file" id="cvPeserta" name="cvPeserta" accept=".pdf" onchange="validateFileSize(this)">
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
        
                    <a href="{{ !empty($encodedFolder) 
                          ? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
                          : route('view.dokumen', ['folder' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                      View / Download CV
                  </a>
                </div>
              @else
                  <!-- Input file jika ID tidak ada -->
                  <input class="form-control @error('cvPeserta') is-invalid @enderror" type="file" name="cvPeserta" accept=".pdf" onchange="validateFileSize(this)" required>
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
                  <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf"  onchange="validateFileSize(this)">
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
        
                  <a href="{{ !empty($encodedFolder) 
                        ? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
                        : route('view.dokumen', ['folder' => $encodedFileName]) }}" 
                    target="_blank" class="btn btn-primary">
                    View / Download LoA
                  </a>
                </div>
              @else
                  <!-- Input file jika ID tidak ada -->
                  <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf" required onchange="validateFileSize(this)">
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
                  <input class="form-control" type="file" id="passPeserta" name="passPeserta" accept=".pdf"  onchange="validateFileSize(this)">
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
        
                    <a href="{{ !empty($encodedFolder) 
                            ? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
                            : route('view.dokumen', ['folder' => $encodedFileName]) }}" 
                      target="_blank" class="btn btn-primary">
                      View / Download Passport
                    </a>
                  </div>
              @else
                  <!-- Input file jika Passport belum ada -->
                  <input class="form-control @error('passPeserta') is-invalid @enderror" type="file" name="passPeserta" required  onchange="validateFileSize(this)" accept="pdf">
                  @error('passPeserta')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              @endif
          </div>
        
          <div class="mb-3">
            <label class="form-label" for="idPeserta">Student ID</label>
            <input class="form-control" type="file" id="idPeserta" name="idPeserta" accept=".jpg, .jpeg, .png" onchange="handleFileChange(event, 'studentIDPreviewDiv', 'studentIDPreview', 240)">
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
      <button class="btn btn-primary" type="submit"  id="submitStuIn" >Submit</button>
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
  </script>
  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


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

  
  let isSubmitting = false;

function confirmSubmission(event) {
    event.preventDefault(); 
    if (isSubmitting) return; // Prevent multiple submissions
    isSubmitting = true;

    const form = event.target; 
    const action = form.action.includes('update') ? 'update' : 'store';
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
            $.ajax({
                url: form.action,
                type: form.method,
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function (response) {
                    isSubmitting = false; // Reset submission state
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: `Data has been ${action === 'update' ? 'updated' : 'saved'}.`,
                            icon: 'success',
                            timer: 4000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    } else {
                        Swal.fire({
                            title: 'Failed!',
                            text: response.message || 'Unable to process data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function (xhr) {
                    isSubmitting = false; // Reset submission state
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'An error occurred while processing the data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } else {
            isSubmitting = false; // Reset submission state if cancelled
        }
    });
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
                  if (imgHeight) previewImg.style.height = imgHeight + 'px';
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
          previewImg.src = '';
      }
  }
</script>


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
                      sendRequest("{{ route('ajukan.bantuan.dana') }}", "POST", { tipe: result.value }, "Bantuan dana berhasil diajukan.");
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
                      sendRequest("{{ route('stuin.approve', $data->id) }}", "POST", {}, "Participant approved successfully!");
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
                      sendRequest("{{ route('stuin.unapprove', $data->id) }}", "POST", {}, "Participant approval has been removed.");
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
                      sendRequest("{{ route('stuin.reject', $data->id) }}", "POST", {}, "Participant has been rejected.");
                  }
              });
          });
      });
  </script>

    <!-- SweetAlert2 CSS -->
    <script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endif
