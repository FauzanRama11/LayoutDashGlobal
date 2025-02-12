@extends('layouts.master') 
@section('content') 

<div class="card">
  <div class="card-header pb-0">
      <div class="row align-items-center">
          <div class="col-md-6 col-12 mb-2">
              <h5 class="mb-1">Form Peserta</h5>
              <span class="text-muted">Untuk mengajukan LOA, Isi Program studi terkait terlebih dahulu!</span>
          </div>

          @if ($data)
          <div class="col-md-6 col-12">
              <div class="d-flex flex-wrap justify-content-md-end justify-content-center gap-2">

                  {{-- Back Button --}}
                  <button type="button" class="btn btn-info" onclick="window.location.href='{{  route('program_stuin.edit', ['id' => $prog_id]) }}'">
                      <i class="fa fa-arrow-left"></i> Back
                  </button>

                    @if ($data->tujuan_prodi)
                        @if ($data->is_loa === 1 && $data->program->is_private_event === 'Tidak' && $data->is_approved == 0)

                            {{-- Unapprove Button --}}
                            @role('fakultas')
                                <button type="button" id="unrequestButton" class="btn btn-warning">
                                    <i class="fa fa-times-circle"></i> Unrequest
                                </button>
                            @endrole

                        @elseif (($data->loa_url || $data->is_loa === 2) && $data->is_approved == 1)
                             
                            {{-- Ajukan Bantuan Dana Button --}}
                            @if ($data->pengajuan_dana_status === 'EMPTY')
                                <button type="button" id="bantuanButton" class="btn btn-secondary">
                                    <i class="fa fa-hand-holding-usd"></i> Ajukan Bantuan Dana
                                </button>
                            @endif

                        @elseif ($data->is_loa === null || $data->is_loa === 0)
                            @role('fakultas')
                                @if ($data->program->is_private_event === 'Tidak')
                                    {{-- Approve Button --}}
                                    <button type="button" id="requestButton" class="btn btn-success">
                                        <i class="fa fa-check-circle"></i> Request for LOA
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
                    @elseif ($data->program->is_private_event === 'Tidak' && $data->is_approved === 0)
                            
                        {{-- Reject Button --}}
                        <button type="button" id="rejectButton" class="btn btn-danger">
                            <i class="fa fa-ban"></i> Reject
                        </button>
                
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
              <option value="Laki-Laki" {{ old('jkPeserta', $data->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
              <option value="Perempuan" {{ old('jkPeserta', $data->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
              <option value="Other" {{ old('jkPeserta', $data->jenis_kelamin ?? '') == 'Other' ? 'selected' : '' }}>Prefer not to disclose</option>
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
          
            <!-- UPLOAD FILE PHOTO -->
            <div class="mb-3">
                <label class="form-label" for="photo_url">Photo</label>
                <input 
                    class="form-control"  
                    type="file" 
                    id="photo_url" 
                    name="photo_url" 
                    accept=".jpg, .jpeg, .png" 
                    onchange="handleImageChange(event, 'photoPreviewDiv', 'photoPreview', 'photoWarning', 240)" 
                    {{ isset($data) ? '' : 'required' }}>
            </div>

            <!-- Preview Gambar -->
            <div class="mb-3">
                <div class="col-sm-12 p-3 justify-content-center align-items-center 
                    {{ isset($data->photo_url) ? 'd-flex border border-3' : 'd-none' }}" 
                    id="photoPreviewDiv">

                    @if(isset($data->photo_base64) && !empty($data->photo_base64))
                        
                        <img id="photoPreview" 
                            src="{{ $data->photo_base64 }}" 
                            alt="Photo Preview" 
                            class="img-fluid" 
                            style="height: 240px; object-fit: cover;">
                    
                    @elseif(isset($data->photo_url) && empty($data->photo_base64))
                        
                        <p id="photoWarning" class="text-muted">Gambar tidak dapat ditemukan, silakan perbarui!</p>

                        <img id="photoPreview" 
                        src="" 
                        alt="Photo Preview" 
                        class="img-fluid" 
                        style="height: 240px; object-fit: cover; display: none;">
                    
                    @else
                        <img id="photoPreview" 
                            src="" 
                            alt="Photo Preview" 
                            class="img-fluid" 
                            style="height: 240px; object-fit: cover; display: none;">
                    @endif
                </div>
            </div>

            <!-- UPLOAD FILE CV -->
            <div class="mb-3">
                <label class="form-label" for="cv_url">CV</label>
                
                @if (isset($data) && !empty($data->cv_url))
                    <input class="form-control" type="file" id="cv_url" name="cv_url" accept=".pdf" onchange="handlePDFChange(event, 'cvPDFLink')">
                    <div class="form-text mb-3">Upload a new file to replace the existing document (optional).</div>
            
                    @php
                        $filePath = ltrim(str_replace('repo/', '', $data->cv_url), '/');
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);

                        $encodedFileName = urlencode($fileName);
                        $encodedFolder = urlencode($folder);
                    @endphp
                    
                    <div class="row mt-2 d-flex align-items-center gap-2">
                        <div class="col-auto d-flex justify-content-start">
                            <a href="{{ !empty($encodedFolder) 
                                ? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
                                : route('view.dokumen', ['folder' => $encodedFileName]) }}" 
                            target="_blank" 
                            class="btn btn-primary">
                                View / Download Old CV
                            </a>
                        </div>

                        <!-- Tombol "View / Download New CV" (Hanya tampil jika ada file baru) -->
                        <div class="col-auto d-flex justify-content-start">
                            <div id="cvPDFLink" class="d-none">
                                <a id="pdfViewer" href="#" target="_blank" class="btn btn-secondary"> 
                                    View / Download New CV
                                </a>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Jika belum ada CV sebelumnya -->
                    <input class="form-control @error('cv_url') is-invalid @enderror" 
                           type="file" 
                           id="cv_url" 
                           name="cv_url"  
                           accept=".pdf" 
                           onchange="handlePDFChange(event, 'cvPDFLink')" 
                           required>
                    <div class="invalid-feedback">CV wajib diisi.</div>

                    
                    <div id="cvPDFLink" class="mt-2 mb-3 d-none">
                        <a id="pdfViewer" href="#" target="_blank" class="btn btn-primary">View / Download CV</a>
                    </div>

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
          {{-- <div class="mb-3">
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
          </div> --}}
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
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
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
        <div class="col-md-6">

                @if(isset($data))
                @php
                    // Ambil nilai lama jika ada, atau set default dari database
                    $selectedIdentity = old('selected_id', $data->selected_id ?? '');

                    // Prioritaskan passport kalau ada
                    if (!empty($data->passport_url)) {
                        $selectedIdentity = 'passport'; 
                    } elseif (!empty($data->student_id_url)) {
                        $selectedIdentity = 'student_id';
                    }
                @endphp

                <div class="mb-3">
                    <label class="form-label" for="selected_id">Selected Identity <span class="text-danger">*</span></label>
                    <select class="form-select" id="selected_id" name="selected_id" required {{ isset($data) && !empty($selectedIdentity) ? 'disabled' : '' }}>
                        <option value="">Selected Identity</option>
                        <option value="student_id" {{ $selectedIdentity == 'student_id' ? 'selected' : '' }}>Student ID</option>
                        <option value="passport" {{ $selectedIdentity == 'passport' ? 'selected' : '' }}>Passport</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Hidden Input -->
                <input type="hidden" name="selected_id" value="{{ $selectedIdentity }}">
            @else
                <div class="mb-3">
                    <label class="form-label" for="selected_id">Selected Identity <span class="text-danger">*</span></label>
                    <select class="form-select" id="selected_id" name="selected_id" required>
                        <option value="">Selected Identity</option>
                        <option value="student_id">Student ID</option>
                        <option value="passport">Passport</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label" for="homePeserta">Home Address</label>
                <textarea class="form-control" id="homePeserta" name="homePeserta" placeholder="Home Address">{{ old('homePeserta', $data->home_address ?? '') }}</textarea>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-md-6">

            @if(isset($data) && $selectedIdentity  === 'student_id')

                    <div class="mb-3">
                        <label class="form-label" for="student_no">Student Identity Number</label>
                        <input class="form-control" id="student_no" name="student_no" placeholder="Enter your Student Id Number" value="{{ old('student_no', $data->student_no ?? '') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    @php
                        $isPdf = isset($data->student_id_url) && Str::endsWith($data->student_id_url, '.pdf');
                        $isImage = isset($data->student_id_url) && !$isPdf;
                        
                        $filePath = isset($data->student_id_url) ? ltrim(str_replace('repo/', '', $data->student_id_url), '/') : '';
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        $encodedFileName = urlencode($fileName);
                        $encodedFolder = urlencode($folder);
                    @endphp

                    <!-- UPLOAD FILE STUDENT ID -->
                    <div class="mb-3">
                        <label class="form-label" for="student_id_url">Student ID</label>
                        <input 
                            class="form-control"  
                            type="file" 
                            id="student_id_url" 
                            name="student_id_url" 
                            accept=".pdf, .jpg, .jpeg, .png" 
                            onchange="handleFileChange(event, 'studentIdPreviewDiv', 'studentIdPreview', 'studentIdPDFLinkOld', 'studentIdPDFLinkNew', 240)" 
                            {{ isset($data) ? '' : 'required' }}>
                    </div>

                    <!-- PREVIEW GAMBAR STUDENT ID -->
                    @if($isImage)
                    <div class="mb-3">
                        <div class="col-sm-12 p-3 justify-content-center align-items-center d-flex border border-3" 
                            id="studentIdPreviewDiv">
                            <img id="studentIdPreview" 
                                src="{{ $data->id_base64 }}" 
                                alt="Student ID Preview" 
                                class="img-fluid" 
                                style="height: 240px; object-fit: cover;">
                        </div>
                    </div>
                    @else
                    <div class="col-sm-12 p-3 justify-content-center align-items-center d-none" id="studentIdPreviewDiv">
                        <img id="studentIdPreview" src="" alt="Photo Preview" class="img-fluid" 
                            style="height: 240px; object-fit: cover; display: none;">
                    </div>
                    @endif

                    <!-- LINK DOWNLOAD STUDENT ID -->
                    @if($isPdf)
                        <div id="studentIdPDFLinkOld" class="mt-2 mb-3">
                            <a href="{{ !empty($encodedFolder) 
                                ? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
                                : route('view.dokumen', ['folder' => $encodedFileName]) }}" 
                            target="_blank" 
                            class="btn btn-primary">
                                View / Download Old Student ID (PDF)
                            </a>
                        </div> 
                    @endif

                    <!-- LINK DOWNLOAD FILE BARU (SETELAH UPLOAD) -->
                    <div id="studentIdPDFLinkNew" class="mt-2 mb-3 d-none">
                        <a id="studentIdPDFViewerNew" href="#" target="_blank" class="btn btn-secondary">
                            View / Download New Student ID
                        </a>
                    </div>
                        
            @elseif(isset($data) &&  $selectedIdentity  === 'passport')
            
                    <div class="mb-3">
                        <label class="form-label" for="passport_no">Paspport Number</label>
                        <input class="form-control" id="passport_no" name="passport_no" placeholder="Nomor Passport" value="{{ old('passport_no', $data->passport_no ?? '') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    @php
                    $isPdf = isset($data->passport_url) && Str::endsWith($data->passport_url, '.pdf');
                    $isImage = isset($data->passport_url) && !$isPdf;
                
                    $filePath = isset($data->passport_url) ? ltrim(str_replace('repo/', '', $data->passport_url), '/') : '';
                    $segments = explode('/', $filePath);
                    $fileName = array_pop($segments);
                    $folder = implode('/', $segments);
                    $encodedFileName = urlencode($fileName);
                    $encodedFolder = urlencode($folder);
                @endphp
                
                <!-- UPLOAD FILE PASSPORT -->
                <div class="mb-3">
                    <label class="form-label" for="passport_url">Passport Identity Page</label>
                    <input 
                        class="form-control"  
                        type="file" 
                        id="passport_url" 
                        name="passport_url" 
                        accept=".pdf, .jpg, .jpeg, .png" 
                        onchange="handleFileChange(event, 'passportPreviewDiv', 'passportPreview', 'passportPDFLinkOld', 'passportPDFLinkNew', 240)" 
                        {{ isset($data) ? '' : 'required' }}>
                </div>
                
                <!-- PREVIEW GAMBAR PASSPORT -->
                @if($isImage)
                    <div class="mb-3">
                        <div class="col-sm-12 p-3 justify-content-center align-items-center d-flex border border-3" 
                            id="passportPreviewDiv">
                            <img id="passportPreview" 
                                src="{{ $data->pass_base64 }}" 
                                alt="Passport Preview" 
                                class="img-fluid" 
                                style="height: 240px; object-fit: cover;">
                        </div>
                    </div>
                @else
                    <div class="col-sm-12 p-3 justify-content-center align-items-center d-none" id="passportPreviewDiv">
                        <img id="passportPreview" src="" alt="Passport Preview" class="img-fluid" 
                            style="height: 240px; object-fit: cover; display: none;">
                    </div>
                @endif
                
                <!-- LINK DOWNLOAD PASSPORT -->
                @if($isPdf)
                    <div id="passportPDFLinkOld" class="mt-2 mb-3">
                        <a href="{{ !empty($encodedFolder) 
                            ? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
                            : route('view.dokumen', ['folder' => $encodedFileName]) }}" 
                        target="_blank" 
                        class="btn btn-primary">
                            View / Download Old Passport (PDF)
                        </a>
                    </div> 
                @endif
                
                <!-- LINK DOWNLOAD FILE BARU (SETELAH UPLOAD) -->
                <div id="passportPDFLinkNew" class="mt-2 mb-3 d-none">
                    <a id="passportPDFViewerNew" href="#" target="_blank" class="btn btn-secondary">
                        View / Download New Passport
                    </a>
                </div>
                

            @else

                {{-- STUDENT ID FIELD --}}
                <div class="studentid_field" style="display: none;">

                    <div class="mb-3">
                        <label class="form-label" for="student_no">Student Identity Number</label>
                        <input class="form-control" id="student_no" name="student_no" placeholder="Enter your Student Id Number" value="{{ old('student_no', $data->student_no ?? '') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    @php
                        $isPdf = isset($data->student_id_url) && Str::endsWith($data->student_id_url, '.pdf');
                        $isImage = isset($data->student_id_url) && !$isPdf;
                        
                        $filePath = isset($data->student_id_url) ? ltrim(str_replace('repo/', '', $data->student_id_url), '/') : '';
                        $segments = explode('/', $filePath);
                        $fileName = array_pop($segments);
                        $folder = implode('/', $segments);
                        $encodedFileName = urlencode($fileName);
                        $encodedFolder = urlencode($folder);
                    @endphp

                    <!-- UPLOAD FILE STUDENT ID -->
                    <div class="mb-3">
                        <label class="form-label" for="student_id_url">Student ID</label>
                        <input 
                            class="form-control"  
                            type="file" 
                            id="student_id_url" 
                            name="student_id_url" 
                            accept=".pdf, .jpg, .jpeg, .png" 
                            onchange="handleFileChange(event, 'studentIdPreviewDiv', 'studentIdPreview', 'studentIdPDFLinkOld', 'studentIdPDFLinkNew', 240)" 
                            {{ isset($data) ? '' : 'required' }}>
                    </div>

                    <!-- PREVIEW GAMBAR STUDENT ID -->
                    @if($isImage)
                    <div class="mb-3">
                        <div class="col-sm-12 p-3 justify-content-center align-items-center d-flex border border-3" 
                            id="studentIdPreviewDiv">
                            <img id="studentIdPreview" 
                                src="{{ $data->id_base64 }}" 
                                alt="Student ID Preview" 
                                class="img-fluid" 
                                style="height: 240px; object-fit: cover;">
                        </div>
                    </div>
                    @else
                    <div class="col-sm-12 p-3 justify-content-center align-items-center d-none" id="studentIdPreviewDiv">
                        <img id="studentIdPreview" src="" alt="Photo Preview" class="img-fluid" 
                            style="height: 240px; object-fit: cover; display: none;">
                    </div>
                    @endif

                    <!-- LINK DOWNLOAD STUDENT ID -->
                    @if($isPdf)
                        <div id="studentIdPDFLinkOld" class="mt-2 mb-3">
                            <a href="{{ !empty($encodedFolder) 
                                ? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
                                : route('view.dokumen', ['folder' => $encodedFileName]) }}" 
                            target="_blank" 
                            class="btn btn-primary">
                                View / Download Old Student ID (PDF)
                            </a>
                        </div> 
                    @endif

                    <!-- LINK DOWNLOAD FILE BARU (SETELAH UPLOAD) -->
                    <div id="studentIdPDFLinkNew" class="mt-2 mb-3 d-none">
                        <a id="studentIdPDFViewerNew" href="#" target="_blank" class="btn btn-secondary">
                            View / Download New Student ID
                        </a>
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
            @endif
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

{{-- VALIDATE FORM --}}
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

{{-- SUBMISSION SWEET ALERT --}}
<script>
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
                                text: JSON.stringify(response.peserta, null, 2),
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
</script>


{{-- HANYA MENERIMA APPLICATION/IMAGE --}}
<script>
    function handleImageChange(event, previewDivId, previewImgId, warningId, imgHeight = null) {
        const input = event.target;
        const file = input.files[0];

        if (!file) {
            resetPreview(previewDivId, previewImgId, warningId);
            return;
        }

        // Validasi ukuran file (maksimal 5MB)
        const maxSize = 2 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            Swal.fire({
                title: 'File terlalu besar!',
                text: 'Ukuran file melebihi 5 MB. Harap unggah file yang lebih kecil.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            input.value = "";
            resetPreview(previewDivId, previewImgId, warningId);
            return;
        }

        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);
        const warningMessage = document.getElementById(warningId);

        if (!previewDiv || !previewImg) {
            console.error(`Elemen preview tidak ditemukan: ${previewDivId}, ${previewImgId}`);
            return;
        }

        // Jika user upload gambar baru, hilangkan warning message
        if (warningMessage) {
            warningMessage.style.display = 'none';
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            previewImg.style.height = imgHeight ? imgHeight + 'px' : '';

            previewDiv.classList.remove('d-none');
            previewDiv.classList.add('d-flex', 'border', 'border-3');
        };
        reader.readAsDataURL(file);
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

    function resetPreview(previewDivId, previewImgId, warningId) {
        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);
        const warningMessage = document.getElementById(warningId);

        if (previewDiv && previewImg) {
            previewDiv.classList.add('d-none');
            previewDiv.classList.remove('d-flex', 'border', 'border-3');
            previewImg.style.display = 'none';
            previewImg.src = "";
        }

        if (warningMessage) {
            warningMessage.style.display = 'block'; // Pastikan warning tetap muncul jika tidak ada file baru
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
    function handleFileChange(event, previewDivId, previewImgId, pdfLinkOldId, pdfLinkNewId, imgHeight = null) {
        const input = event.target;
        const file = input.files[0];

        if (!file) {
            resetAllPreview(previewDivId, previewImgId, pdfLinkOldId, pdfLinkNewId);
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
            resetAllPreview(previewDivId, previewImgId, pdfLinkOldId, pdfLinkNewId);
            return;
        }

        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);
        const pdfLinkOld = document.getElementById(pdfLinkOldId);
        const pdfLinkNew = document.getElementById(pdfLinkNewId);
        const pdfViewer = document.getElementById(pdfLinkNewId.replace("Link", "Viewer"));

        resetAllPreview(previewDivId, previewImgId, pdfLinkOldId, pdfLinkNewId);

        const fileURL = URL.createObjectURL(file);

        if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
            // Jika file adalah gambar, tampilkan preview
            previewImg.src = fileURL;
            previewImg.style.display = 'block';
            previewImg.style.height = imgHeight ? imgHeight + 'px' : '';

            previewDiv.classList.remove('d-none');
            previewDiv.classList.add('d-flex', 'border', 'border-3');

            pdfLinkOld.classList.add('d-none'); // Sembunyikan PDF link
        } else if (file.type === 'application/pdf') {
            // Jika file adalah PDF, tampilkan tombol download baru
            pdfLinkNew.classList.remove('d-none');
            pdfViewer.href = fileURL;
            pdfViewer.target = "_blank";

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
            resetAllPreview(previewDivId, previewImgId, pdfLinkOldId, pdfLinkNewId);
        }
    }

    function resetAllPreview(previewDivId, previewImgId, pdfLinkOldId, pdfLinkNewId) {
        const previewDiv = document.getElementById(previewDivId);
        const previewImg = document.getElementById(previewImgId);
        const pdfLinkOld = document.getElementById(pdfLinkOldId);
        const pdfLinkNew = document.getElementById(pdfLinkNewId);
        const pdfViewer = document.getElementById(pdfLinkNewId.replace("Link", "Viewer"));

        if (previewDiv && previewImg) {
            previewDiv.classList.add('d-none');
            previewDiv.classList.remove('d-flex', 'border', 'border-3');
            previewImg.style.display = 'none';
            previewImg.src = '';
        }

        if (pdfLinkOld) {
            pdfLinkOld.classList.remove('d-none'); // Biarkan link lama tetap ada
        }

        if (pdfLinkNew && pdfViewer) {
            pdfLinkNew.classList.add('d-none');
            pdfViewer.href = "#";
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selected_program = document.getElementById('selected_id');
    
        selected_program.addEventListener('change', function () {
            const selectedValue = this.value;
    
            // Ambil semua elemen dengan kelas yang sesuai
            const passport_field = document.querySelectorAll('.passport_field');
            const studentid_field = document.querySelectorAll('.studentid_field');
    
            // Sembunyikan semua form
            hideForms(passport_field);
            hideForms(studentid_field);
    
            // Tampilkan form yang sesuai dengan pilihan
            if (selectedValue === 'passport') {
                showForms(passport_field);
            } else if (selectedValue === 'student_id') {
                showForms(studentid_field);
            } 
        });
    
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
    
                // Cek apakah elemen awalnya memiliki required
                if (!isDisabled) {
                    if (input.dataset.originalRequired === "true") {
                        input.setAttribute('required', true);
                    }
                } else {
                    // Simpan status required sebelum dinonaktifkan
                    if (input.hasAttribute('required')) {
                        input.dataset.originalRequired = "true"; // Tandai bahwa awalnya required
                    } else {
                        input.dataset.originalRequired = "false"; // Tandai bahwa awalnya tidak required
                    }
    
                    input.removeAttribute('required');
                }
            });
        }
    
    
    });
    
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

          $('#requestButton').click(function () {
              Swal.fire({
                  title: "Requesting for LOA?",
                  text: "This participant will be officially recognized by Airlangga Global Engagement.",
                  icon: "success",
                  showCancelButton: true,
                  confirmButtonColor: "#28a745",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Yes, Request LOA!"
              }).then((result) => {
                  if (result.isConfirmed) {
                      sendRequest("{{ route('stuin.request', $data->id) }}", "POST", {}, "LOA request has sent successfully!");
                  }
              });
          });

          $('#unrequestButton').click(function () {
              Swal.fire({
                  title: "Uncommit the LOA request",
                  text: "This will remove the request status for this participant.",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#ffc107",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Yes, Undo request!"
              }).then((result) => {
                  if (result.isConfirmed) {
                      sendRequest("{{ route('stuin.unrequest', $data->id) }}", "POST", {}, "Participant LOA Request has been removed.");
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
@endif
