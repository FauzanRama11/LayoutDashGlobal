@extends('layouts.master') 
@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <h5>Form Program </h5>
    <span>This is Optional Notes</span>

    @if(isset($data))
          <div class="d-flex justify-content-end align-content-center">
              @if(isset($data) && isset($data->is_approved) && $data->is_approved != 1)
                  <form action="" method="POST">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-primary">Approved</button>
                  </form>
                  <button type="button" id="rejectButton1" class="btn btn-danger mx-2">Rejected</button>
              @endif
          </div>

          <!-- Form untuk Revise -->
          <div id="reNotes" style="display: none;">
              <form action="" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="mb-3">
                      <label class="form-label" for="revisionNotes">Notes</label>
                      <textarea class="form-control" id="revisionNotes" name="notes" placeholder="Notes" required>{{ old('notes', isset($data) ? $data->revision_note : '') }}</textarea>
                      <div class="invalid-feedback">Notes wajib diisi.</div>
                  </div>
                  <button type="submit" class="btn btn-warning">Click to Revise</button>
              </form>
          </div>

          <!-- Form untuk Reject -->
          <div id="reNotes2" style="display: none;">
              <form action="" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="mb-3">
                      <label class="form-label" for="rejectNotes">Notes</label>
                      <textarea class="form-control" id="rejectNotes" name="notes" placeholder="Notes" required>{{ old('notes', isset($data) ? $data->revision_note : '') }}</textarea>
                      <div class="invalid-feedback">Notes wajib diisi.</div>
                  </div>
                  <button type="submit" class="btn btn-danger">Click to Reject</button>
              </form>
          </div>
      @endif
  </div>
  <form class="was-validated" action="{{ $data ? route('stuin_peserta.update') : route('stuin_peserta.store') }}"  method="post" enctype="multipart/form-data">
    

    @csrf
    <!-- Tambahkan method PUT jika update -->
    @if ($data)
        @method('PUT')
        <input type="hidden" name="peserta_id" value="{{ $data->id }}">
    @endif

    <!-- Informasi Umum -->
    <div class="card-header pb-0">
      <h5>Umum</h5>
      <span>Informasi Umum Peserta</span>
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
            <div class="invalid-feedback">Nama wajib diisi.</div>
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
            <label class="form-label" for="kebangsaan">Kebangsaan</label>
            <select class="form-select js-example-basic-single" id="kebangsaan" name="kebangsaan">
              @foreach($country as $item)
                <option value="{{ $item->id }}" {{ old('kebangsaan', $data->kebangsaan ?? '') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
              @endforeach
            </select>
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

          <div class="mb-3">
            <label class="form-label" for="prodi_asal">Prodi Asal</label>
            <input class="form-control" id="prodi_asal" name="prodi_asal" placeholder="Prodi Tujuan" required
            value="{{ old('prodi_asal', $data->prodi_asal ?? '') }}">
            <div class="invalid-feedback">Prodi wajib diisi.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="fakultas_asal">Fakultas Asal</label>
            <input class="form-control" id="fakultas_asal" name="fakultas_asal" placeholder="Fakultas Asal" required
            value="{{ old('fakultas_asal', $data->fakultas_asal ?? '') }}">
            <div class="invalid-feedback">Fakultas wajib diisi.</div>
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
          </div>
          
          {{-- Photo URL --}}
          <div class="mb-3">
            <label class="form-label" for="fotoPeserta">Photo</label>
            <input class="form-control" type="file" id="fotoPeserta" name="fotoPeserta" accept=".jpg, .jpeg, .png" onchange="previewImage(event, 'photoPreviewDiv', 'photoPreview')">
            <div class="invalid-feedback">Photo wajib diisi.</div>
          </div>
          
          <div class="mt-3">
            <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="photoPreviewDiv" style="display: {{ isset($data) && !empty($data->photo_base64) ? 'block' : 'none' }};">
                <img id="photoPreview" src="{{ isset($data) && !empty($data->photo_base64) ? $data->photo_base64 : '' }}" alt="Photo Preview" class="img-fluid" style="width: 300px; height: 300px; object-fit: cover;">
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
                    <a href="{{ route('view.dokumen', basename($data->cv_url)) }}" target="_blank" class="btn btn-primary">
                        View / Download Document
                    </a>
                </div>
              @else
                  <!-- Input file jika ID tidak ada -->
                  <input class="form-control @error('cvPeserta') is-invalid @enderror" type="file" name="cvPeserta">
                  <div class="form-text">Upload a file if needed.</div>
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
      <h5>Approval</h5>
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
                    <a href="{{ route('view.dokumen', basename($data->loa_url)) }}" target="_blank" class="btn btn-primary">
                        View / Download Document
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
          <div class="mb-3">
            <label class="form-label" for="tfakultasPeserta">Fakultas Tujuan</label>
            <select class="form-select js-example-basic-single" id="tfakultasPeserta" name="tfakultasPeserta">
              @foreach($fakultas as $item)
                <option value="{{ $item->id }}">{{ $item->nama_ind }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="tprodiPeserta">Prodi Tujuan</label>
            <select class="form-select js-example-basic-single" id="tprodiPeserta" name="tprodiPeserta">
              @foreach($prodi as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian Imigrasi -->
    <div class="card-header pb-0">
      <h5>Imigrasi</h5>
      <span>Imigrasi Peserta</span>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="noPassPeserta">Nomor Passport</label>
            <input class="form-control" id="noPassPeserta" name="noPassPeserta" placeholder="Nomor Passport" required
            value="{{ old('noPassPeserta', $data->passport_no ?? '') }}">
            <div class="invalid-feedback">Nomor Passport wajib diisi.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="homePeserta">Home Address</label>
            <textarea class="form-control" id="homePeserta" name="homePeserta" placeholder="Home Address" required>{{ old('homePeserta', $data->home_address ?? '') }}</textarea>
            <div class="invalid-feedback">Alamat wajib diisi.</div>
          </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="passPeserta">Passport</label>
            <input class="form-control" type="file" id="passPeserta" name="passPeserta" accept=".pdf">
            <div class="invalid-feedback">Passport wajib diisi.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="idPeserta">Student ID</label>
            <input class="form-control" type="file" id="idPeserta" name="idPeserta" accept=".jpg, .jpeg, .png" onchange="previewImage(event, 'studentIDPreviewDiv', 'studentIDPreview')">
          </div>
          
          <div class="mt-3">
              <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="studentIDPreviewDiv" style="display: {{ isset($data) && !empty($data->id_base64) ? 'block' : 'none' }};">
                  <img id="studentIDPreview" src="{{ isset($data) && !empty($data->id_base64) ? $data->id_base64 : '' }}" alt="Student ID Preview" class="img-fluid" style="width: 300px; height: 300px; object-fit: cover;">
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
function previewImage(event, previewDivId, previewImgId) {
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

<script>

// Logic untuk Revise
  document.getElementById('reviseButton1').addEventListener('click', function () {
      document.getElementById('reNotes').style.display = 'block';
      document.getElementById('reNotes2').style.display = 'none'; // Sembunyikan form reject jika terbuka
  });
  
  // Logic untuk Reject
  document.getElementById('rejectButton1').addEventListener('click', function () {
      document.getElementById('reNotes2').style.display = 'block';
      document.getElementById('reNotes').style.display = 'none'; // Sembunyikan form revise jika terbuka
  });
  
</script>
