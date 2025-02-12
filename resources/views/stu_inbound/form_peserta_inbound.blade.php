@extends('layouts.master')

@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div >
            <h5 class="mb-0">Amerta/Lingua Data Form</h5>
            <span>Please upload LOA an fill the required form to Approve the student eligibility</span>
        </div>

        <div class="d-flex justify-content-end align-items-center gap-2 mt-3">

            <button type="button" class="btn btn-info" onclick="window.location.href='{{ $processedData['type'] === 'amerta' ? route('am_pendaftar') : route('li_pendaftar') }}'">
                <i class="fa fa-arrow-left"></i> Back
            </button>

            <button type="button" class="btn btn-success" id="confirmUpdate">
                <i class="fa fa-save"></i> Update Data
            </button>

            {{-- Check Dokumen LOA --}}
            @if ($processedData['loaPeserta'])
                @if ($processedData['is_approve'] === true)
                    <button type="button" id="unapproveButton" class="btn btn-warning">
                        <i class="fa fa-times-circle"></i> Unapprove
                    </button>
                @else
                    
                    <button type="button" id="approveButton" class="btn btn-success">
                        <i class="fa fa-check-circle"></i> Approve
                    </button>

                    <button type="button" id="rejectButton" class="btn btn-danger">
                        <i class="fa fa-ban"></i> Reject
                    </button>
                @endif
            @endif
        </div>

    </div>
    <hr>
</div>

<div class="card-body">
    <form id="updateForm" action="{{ route('update_peserta_inbound', ['id' => $processedData['id']]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="type" name="type" disabled>
                        <option value="amerta" {{ old('type', $processedData['type'] ?? '') === 'amerta' ? 'selected' : '' }}>Amerta</option>
                        <option value="lingua" {{ old('type', $processedData['type'] ?? '') === 'lingua' ? 'selected' : '' }}>Lingua</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
        
                <div class="mb-3">
                    <label class="form-label" for="selected_program">Selected Program <span class="text-danger">*</span></label>
                    <select class="form-select" id="selected_program" name="selected_program">
                        @if($processedData['type'] === 'amerta')
                            <option value="AMERTA" {{ old('selected_program', $processedData['selected_program'] ?? '') === 'AMERTA' ? 'selected' : '' }}>Amerta</option>
                            <option value="Amerta Internship" {{ old('selected_program', $processedData['selected_program'] ?? '') === 'Amerta Internship' ? 'selected' : '' }}>Amerta Internship</option>
                            <option value="Amerta Research" {{ old('selected_program', $processedData['selected_program'] ?? '') === 'Amerta Research' ? 'selected' : '' }}>Amerta Research</option>
                        @else
                            <option value="" {{ old('selected_program', $processedData['selected_program'] ?? '') === '' ? 'selected' : '' }}>Lingua</option>
                        @endif
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
        
                <div class="mb-3">
                    <label class="form-label" for="mobility">Intended Mobility <span class="text-danger">*</span></label>
                    <select class="form-select" id="mobility" name="mobility">
                        <option value="vm" {{ old('mobility', $processedData['mobility'] ?? '') === 'vm' ? 'selected' : '' }}>Virtual Mobility</option>
                        <option value="m" {{ old('mobility', $processedData['mobility'] ?? '') === 'm' ? 'selected' : '' }}>Mobility</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="via">Via <span class="text-danger">*</span></label>
                    <select class="form-select" id="via" name="via">
                        <option value="">Select Via</option>
                        <option value="Offline" {{ old('via', $processedData['via'] ?? '') == 'Offline' ? 'selected' : '' }}>Offline</option>
                        <option value="Online" {{ old('via', $processedData['via'] ?? '') == 'Online' ? 'selected' : '' }}>Online</option>
                        <option value="Hybrid" {{ old('via', $processedData['via'] ?? '') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="progCategory">Kategori <span class="text-danger">*</span></label>
                    <select class="js-example-basic-single col-sm-12" id="progCategory" name="progCategory">
                        <option value="">Select category</option>
                        @foreach($category as $item)
                            <option value="{{ $item->name }}" {{ old('progCategory', $processedData['progCategory'] ?? '') == $item->name ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Pilih kategori.</div>
                </div>
            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="email" name="email">{{ old('email', $processedData['email'] ?? '') }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="secondary_email">Secondary Email</label>
                    <textarea class="form-control" id="secondary_email" name="secondary_email">{{ old('secondary_email', $processedData['secondary_email'] ?? '') }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
  </div>
</div>


<div class="card">
  <div class="card-header pb-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Bagian Kiri: Judul dan Span -->
      <div>
        @if($processedData['selected_program'] === 'Amerta Regular' || $processedData['selected_program'] === 'AMERTA')
            <h5 class="mb-0">Amerta Regular</h5>
            <span>The personal information of the student</span>
        @elseif($processedData['selected_program'] === 'Amerta Internship')
            <h5 class="mb-0">Amerta Internship</h5>
            <span>The personal information of the student</span>
        @elseif($processedData['selected_program'] === 'Amerta Research')
            <h5 class="mb-0">Amerta Research</h5>
            <span>The personal information of the student</span>
        @elseif($processedData['selected_program'] === 'Lingua' || $processedData['selected_program'] === '')
            <h5 class="mb-0">Lingua</h5>
            <span>The personal information of the student</span>
        @endif
        
      </div>

      <!-- Bagian Kanan: Tombol -->
      <div class="d-flex row">
        <div class="mt-3 text-end">
            @if ($processedData['is_approve'] === true)
                <span class="badge badge-primary">Approved</span>
            @elseif ($processedData['is_approve'] === false)
                <span class="badge badge-danger">Rejected</span>
            @else
                <span class="badge badge-info">Not Processed</span>
            @endif
        </div>
      </div>
    </div>
    <hr>
  </div>

  <div class="card-body">
      <h5 class="mt-3">Approval</h5>
      <hr>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="loaPeserta">LoA <span class="text-danger">*</span></label>
                @if (isset($processedData) && !empty($processedData['loaPeserta']))
                    <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf" onchange="validateFileSize(this)">
                    <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                    
                    <div class="mt-2">
                        @php
                            $filePath = ltrim(str_replace('repo/', '', $processedData['loaPeserta']), '/');
                            $segments = explode('/', $filePath);
                            $fileName = array_pop($segments);
                            $folder = implode('/', $segments);
                            
                            $encodedFileName = urlencode($fileName);
                            $encodedFolder = urlencode($folder);
                        @endphp
            
                        <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                        target="_blank" class="btn btn-primary">
                            View / Download LOA
                        </a>
                    </div>
                @else
                    <!-- Input file jika ID tidak ada -->
                    <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf" required onchange="validateFileSize(this)">
                    <div class="form-text"></div>
                @endif
            </div>
        </div>

        @if($processedData['selected_program'] === 'Amerta Internship' || $processedData['selected_program'] === 'Amerta Research')
            <div class="col-md-6">
                <!-- Fakultas Tujuan -->
                <div class="mb-3">
                    <label class="form-label" for="tFakultasPeserta">Fakultas Tujuan <span class="text-danger">*</span></label>
                    <select class="form-select js-example-basic-single" id="tFakultasPeserta" name="tFakultasPeserta" required>
                        <option value="">Select Unit related to in Universitas Airlangga</option>
                        @foreach($unit as $item)
                            <option value="{{ $item->id }}" 
                                {{ old('tFakultasPeserta', $processedData['tFakultasPeserta'] ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_ind }}
                            </option>
                        @endforeach
                    </select> 
                    <div class="invalid-feedback">This field is required.</div>
                </div>
                
                <!-- Prodi Tujuan -->
                <div class="mb-3">
                    <label class="form-label" for="tProdiPeserta">Prodi Tujuan <span class="text-danger">*</span></label>
                    <select class="form-select js-example-basic-single" id="tProdiPeserta" name="tProdiPeserta" required>
                        <option value="">Select Major related to in Universitas Airlangga</option>
                        @foreach($prodi as $item)
                            <option value="{{ $item->id }}" 
                                {{ old('tProdiPeserta', $processedData['tProdiPeserta'] ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->level }} {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">This field is required.</div>
                </div>  
            </div> 
        @endif   
      </div>

      <h5 class="mt-3">Personal Information</h5>
      <hr>
        <div class="row">
            <div class="col-md-6">
                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label" for="fullname">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="e.g: Rizal Akbar Johar" value="{{ old('email', $processedData['fullname'] ?? '') }}">
                    <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                </div>

                <!-- First Name -->
                <div class="mb-3">
                    <label class="form-label" for="firstname">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="e.g: Rizal Akbar"  value="{{ old('firstname', $processedData['firstname'] ?? '') }}">
                    <div class="invalid-feedback">Nama depan wajib diisi.</div>
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label class="form-label" for="lastname">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="e.g: Johar"  value="{{ old('lastname', $processedData['lastname'] ?? '') }}">
                    <div class="invalid-feedback">Nama belakang wajib diisi.</div>
                </div>

                <!-- Sex -->
                <div class="mb-3">
                    <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                    <select class="form-select" id="sex" name="sex">
                        <option value="M" {{ old('sex', $processedData['sex'] ?? '') === 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('sex', $processedData['sex'] ?? '') === 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                    <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                </div>

                <!-- Place of Birth -->
                <div class="mb-3">
                    <label class="form-label" for="pob">Place of Birth <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pob" name="pob" placeholder="e.g: Surabaya, Indonesia" value="{{ old('pob', $processedData['pob'] ?? '') }}">
                    <div class="invalid-feedback">Tempat lahir wajib diisi.</div>
                </div>

                <!-- Date of Birth -->
                <div class="mb-3">
                    <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $processedData['dob'] ?? '') }}">
                    <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                </div>  
                    
                <!-- Nationality -->
                <div class="mb-3">
                    <label class="form-label" for="nationality">Nationality <span class="text-danger">*</span></label>
                    <select class="form-select js-example-basic-single" id="nationality" name="nationality">
                        <option value="">Select Nationality</option>
                        @foreach($country as $item)
                            <option value="{{ $item->id }}" {{ old('nationality', $processedData['nationality'] ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>                        
                    <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                </div>

                <!-- Telephone Number -->
                <div class="mb-3">
                    <label class="form-label" for="telephone">Telephone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="e.g: +628 1234567" value="{{ old('telephone', $processedData['telephone'] ?? '') }}">
                    <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                </div>

                <!-- Mobile/Cellular Number -->
                <div class="mb-3">
                    <label class="form-label" for="phone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g: +628 1234567" value="{{ old('phone', $processedData['phone'] ?? '') }}">
                    <div class="invalid-feedback">Nomor ponsel wajib diisi.</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Passport Number -->
                <div class="mb-3">
                    <label class="form-label" for="passport_number">Passport Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="passport_number" name="passport_number" placeholder="Enter your Passport number here" value="{{ old('passport_number', $processedData['passport_number'] ?? '') }}">
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Passport Date of Issue -->
                <div class="mb-3">
                    <label class="form-label" for="passport_date_issue">Passport Date of Issue <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="passport_date_issue" name="passport_date_issue" value="{{ old('passport_date_issue', $processedData['passport_date_issue'] ?? '') }}">
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Passport Date of Expiration -->
                <div class="mb-3">
                    <label class="form-label" for="passport_date_exp">Passport Date of Expiration <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="passport_date_exp" name="passport_date_exp" value="{{ old('passport_date_exp', $processedData['passport_date_exp'] ?? '') }}">
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Issuing Authority -->
                <div class="mb-3">
                    <label class="form-label" for="issuing_authority">Issuing Authority <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="issuing_authority" name="issuing_authority" placeholder="e.g: Immigration Office of Surabaya, Indonesia" value="{{ old('issuing_authority', $processedData['issuing_authority'] ?? '') }}">
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Home Address -->
                <div class="mb-3">
                    <label class="form-label" for="address">Home Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="address" name="address" placeholder="Please provide the details such as region, province, country, and postal code" rows="2">{{ old('address', $processedData['address'] ?? '') }}</textarea>
                    <div class="invalid-feedback">Alamat rumah wajib diisi.</div>
                </div>

                <!-- Mailing Address -->
                <div class="mb-3">
                    <label class="form-label" for="mail_address">Mailing Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="mail_address" name="mail_address" placeholder="Please provide the details such as region, province, country, and postal code" rows="2">{{ old('mail_address', $processedData['mail_address'] ?? '') }}</textarea>
                    <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                </div>

                <!-- Indonesian Embassy Address -->
                <div class="mb-3">
                    <label class="form-label" for="embassy_address">Indonesian Embassy Address</label>
                    <textarea class="form-control" id="embassy_address" name="embassy_address" placeholder="Please provide the details of the nearest indonesian embaassy in your respective country, e.g. The embassy of republic indonesia, 233. Jalan Tun Razak, Imbi, 50400 Kuala Lumpur wilayah" 
                    rows="2">{{ old('embassy_address', $processedData['embassy_address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Educational Qualification -->
        <h5 class="mt-3">Educational Qualification</h5>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="form-label" for="university">Home University <span class="text-danger">*</span></label>
                    <select class="form-select js-example-basic-single" id="university" name="university">
                        @foreach($univ as $item)
                            <option value="{{ $item->id }}" {{ old('university', $processedData['university'] ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="degree">Degree Being Taken at University <span class="text-danger">*</span></label>
                    <select class="form-select" id="degree" name="degree">
                        <option value="">Select Degree</option>
                        <option value="Bachelor" {{ old('degree', $processedData['degree'] ?? '') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                        <option value="Master" {{ old('degree', $processedData['degree'] ?? '') == 'Master' ? 'selected' : '' }}>Master</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="faculty">Faculty (Home University) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="faculty" name="faculty" 
                        value="{{ old('faculty', $processedData['faculty'] ?? '') }}" 
                        placeholder="Science, etc.">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="major">Major (Home University) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="major" name="major" 
                        value="{{ old('major', $processedData['major'] ?? '') }}" 
                        placeholder="Biotechnology, etc.">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            
            <div class="col-md-6">
                
                @if($processedData['type'] === 'amerta')
                    <div class="mb-3">
                        <label class="form-label" for="gpa">GPA <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="gpa" name="gpa" 
                            value="{{ old('gpa', $processedData['gpa'] ?? '') }}" 
                            placeholder="Your GPA/ Max GPA (e.g: 3.3/4.0 or 4.1/55.0)">
                        <div class="invalid-feedback">GPA wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="year_entry">Year of Entry <span class="text-danger">*</span></label>
                        <input type="month" class="form-control" id="year_entry" name="year_entry" 
                            value="{{ old('year_entry', $processedData['year_entry'] ?? '') }}">
                        <div class="invalid-feedback">Tahun masuk wajib diisi.</div>
                    </div>
                @endif
                
                <div class="mb-3">
                    <label class="form-label" for="native">Medium of Instruction in Home University <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="native" name="native" 
                        value="{{ old('native', $processedData['native'] ?? '') }}" 
                        placeholder="English, Indonesian">
                    <div class="invalid-feedback">Bahasa asli wajib diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="english_score">English Proficiency Score <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="english_score" name="english_score" 
                        value="{{ old('english_score', $processedData['english_score'] ?? '') }}" 
                        placeholder="Your English Score (e.g: TOEFL 90, IELTS 6.5)">
                    <div class="invalid-feedback">Nilai Bahasa Inggris wajib diisi.</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <h5 class="mt-3">Particular Next of Kin</h5>
                <hr>
                
                <div class="mb-3">
                    <label class="form-label" for="kin_name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="kin_name" name="kin_name" 
                        value="{{ old('kin_name', $processedData['kin_name'] ?? '') }}" 
                        placeholder="e.g: Adi Johan">
                    <div class="invalid-feedback">Nama wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_relation">Relationship <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="kin_relation" name="kin_relation" 
                        value="{{ old('kin_relation', $processedData['kin_relation'] ?? '') }}" 
                        placeholder="e.g: Father">
                    <div class="invalid-feedback">Hubungan wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_address">Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="kin_address" name="kin_address" 
                        placeholder="Please provide the details such as region, province, country, and postal code" 
                        rows="2">{{ old('kin_address', $processedData['kin_address'] ?? '') }}</textarea>
                    <div class="invalid-feedback">Alamat wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="kin_email" name="kin_email" 
                        value="{{ old('kin_email', $processedData['kin_email'] ?? '') }}" 
                        placeholder="Email Kerabat">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_telephone">Telephone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="kin_telephone" name="kin_telephone" 
                        value="{{ old('kin_telephone', $processedData['kin_telephone'] ?? '') }}" 
                        placeholder="e.g: +628 1234567">
                    <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_phone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="kin_phone" name="kin_phone" 
                        value="{{ old('kin_phone', $processedData['kin_phone'] ?? '') }}" 
                        placeholder="e.g: +628 1234567">
                </div>
            </div>
            
            <div class="col-md-6">

                <h5 class="mt-3">Details of The Contact Person at Home University</h5>
                <hr>

                <div class="mb-3">
                    <label class="form-label" for="pic_name">Contact Person at your Home University <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pic_name" name="pic_name" 
                        value="{{ old('pic_name', $processedData['pic_name'] ?? '') }}" 
                        placeholder="e.g: Adi Johan">
                    <div class="invalid-feedback">Kontak wajib diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_position">Position of the Contact Person at your Home University <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pic_position" name="pic_position" 
                        value="{{ old('pic_position', $processedData['pic_position'] ?? '') }}" 
                        placeholder="e.g: Head of International Office">
                    <div class="invalid-feedback">Jabatan wajib diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="pic_email" name="pic_email" 
                        value="{{ old('pic_email', $processedData['pic_email'] ?? '') }}" 
                        placeholder="">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_telephone">Telephone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="pic_telephone" name="pic_telephone" 
                        value="{{ old('pic_telephone', $processedData['pic_telephone'] ?? '') }}" 
                        placeholder="e.g: +628 1234567">
                    <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_address">Mailing Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="pic_address" name="pic_address" 
                        placeholder="For Transcript and Certificate shipping" rows="2">{{ old('pic_address', $processedData['pic_address'] ?? '') }}</textarea>
                    <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                </div>
            </div>
        </div>

        <!-- Character Reference -->
        @if($processedData['selected_program'] === 'amerta')
            <h5 class="mt-3">Character Reference</h5>
            <hr>
            
            <div class="mb-3">
                <label class="form-label" for="referee_name">Name of Referee (with Salutation) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="referee_name" name="referee_name" 
                    value="{{ old('referee_name', $processedData['referee_name'] ?? '') }}" 
                    placeholder="e.g: Adi Johan">
                <div class="invalid-feedback">Nama rujukan wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="referee_organization">Organization and Position <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="referee_organization" name="referee_organization" 
                    value="{{ old('referee_organization', $processedData['referee_organization'] ?? '') }}" 
                    placeholder="e.g: Professor at XYZ University">
                <div class="invalid-feedback">Organisasi dan jabatan wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="referee_relation">Relationship to Applicant <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="referee_relation" name="referee_relation" 
                    value="{{ old('referee_relation', $processedData['referee_relation'] ?? '') }}" 
                    placeholder="e.g: Thesis Supervisor">
                <div class="invalid-feedback">Hubungan dengan rujukan wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="referee_email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="referee_email" name="referee_email" 
                    value="{{ old('referee_email', $processedData['referee_email'] ?? '') }}" 
                    placeholder="Masukkan Email Rujukan">
                <div class="invalid-feedback">Email rujukan wajib diisi.</div>
            </div>
        @endif


        {{-- PERPECAHAN SESUAI SELECTED PROGRAM --}}
        @if($processedData['selected_program'] === 'Amerta Regular' || $processedData['selected_program'] === 'AMERTA')

            {{-- CHOICE OF SUBJECT | AMERTA REGULAR --}}
            @if($processedData['type'] === 'amerta')
                <div class="amerta-regular-form">
                    <h5 class="mt-3">Choice of Subjects</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="course1">Course 1 <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single col-sm-12" id="course1" name="course1">
                                    <option value="" selected>Select Course</option>
                                    @foreach($course as $item)
                                        <option value="{{ $item->code }}" 
                                            {{ old('course1', $processedData['course1'] ?? '') == $item->code ? 'selected' : '' }}>
                                            {{ $item->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @for ($i = 2; $i <= 3; $i++)
                                <div class="mb-3">
                                    <label class="form-label" for="course{{ $i }}">Course{{ $i }}</label>
                                    <select class="js-example-basic-single col-sm-12" id="course{{ $i }}" name="course{{ $i }}">
                                        <option value="" selected>Select Course</option>
                                        @foreach($course as $item)
                                            <option value="{{ $item->code }}" 
                                                {{ old("course$i", $processedData["course$i"] ?? '') == $item->code ? 'selected' : '' }}>
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            @endfor
                        </div>
                        <div class="col-md-6">
                            @for ($i = 4; $i <= 6; $i++)
                                <div class="mb-3">
                                    <label class="form-label" for="course{{ $i }}">Course{{ $i }}</label>
                                    <select class="js-example-basic-single col-sm-12" id="course{{ $i }}" name="course{{ $i }}">
                                        <option value="" selected>Select Course</option>
                                        @foreach($course as $item)
                                            <option value="{{ $item->code }}" 
                                                {{ old("course$i", $processedData["course$i"] ?? '') == $item->code ? 'selected' : '' }}>
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- <div class="mb-3 mt-3 d-flex justify-content-between align-content-center">
                        <label class="form-label d-block">
                            Have you ever taken Bahasa Indonesia course? <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="taken_indo" value="No">
                            <label class="switch me-2">
                                <input type="checkbox" id="taken_indo_checkbox" name="taken_indo" value="yes" onchange="toggleStatus('taken_indo_checkbox', 'taken_indo_status')">
                                <span class="switch-state"></span>
                            </label>
                            <span id="taken_indo_status">No</span>
                        </div>
                    </div> --}}
                    
                    <div class="mb-3 d-flex justify-content-between align-content-center">
                        <label class="form-label d-block">
                            Interested in taking Bahasa Indonesia Course as Complementary Course? <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="take_indo" value="No">
                            <label class="switch me-2">
                                <input type="checkbox" id="take_indo_checkbox" name="take_indo" value="yes" 
                                    {{ old('take_indo', $processedData['take_indo'] ?? 'No') == 'yes' ? 'checked' : '' }} 
                                    onchange="toggleStatus('take_indo_checkbox', 'take_indo_status')">
                                <span class="switch-state"></span>
                            </label>
                            <span id="take_indo_status">{{ old('take_indo', $processedData['take_indo'] ?? 'No') == 'yes' ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>
            @endif

        @elseif($processedData['selected_program'] === 'Amerta Internship')
        
        <!-- SECTION FOR AMERTA INTERNSHIP -->
        <div class="amerta-internship-form">
            <h5 class="mt-3">Internship Preferences</h5>
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="field_of_study">Preferred field of internship</label>
                        <input type="text" class="form-control" id="field_of_study" name="field_of_study" 
                            value="{{ old('field_of_study', $processedData['field_of_study'] ?? '') }}" 
                            placeholder="e.g., Tourism, Business, Health, etc.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="outcome">Expected Learning Outcomes <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="outcome" name="outcome" 
                            value="{{ old('outcome', $processedData['outcome'] ?? '') }}" 
                            placeholder="e.g., Logbook, etc.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="experience">Work or volunteer experience (if any) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="experience" name="experience" 
                            value="{{ old('experience', $processedData['experience'] ?? '') }}" 
                            placeholder="experience">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <!-- Starting Date of Internship -->
                    <div class="mb-3">
                        <label class="form-label" for="start_date_prog">Starting Date of Internship <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="start_date_prog" name="start_date_prog" 
                            value="{{ old('start_date_prog', $processedData['start_date_prog'] ?? '') }}">
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- End Date of Internship -->
                    <div class="mb-3">
                        <label class="form-label" for="end_date_prog">End Date of Internship <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="end_date_prog" name="end_date_prog" 
                            value="{{ old('end_date_prog', $processedData['end_date_prog'] ?? '') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            
        </div>


        @elseif($processedData['selected_program'] === 'Amerta Research')

        <!-- ATTACHMENT AMERTA RESEARCH -->
        <div class="amerta-research-form">
            <h5 class="mt-3">Research Study</h5>
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="cv">Research Proposal <span class="text-danger">*</span></label>
        
                        @if(isset($processedData) && !empty($processedData['research_proposal']))
                            <input class="form-control" type="file" id="research_proposal" name="research_proposal" accept=".pdf" onchange="validateFileSize(this)">
                            <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                            <div class="mt-2">
                                @php
                                    $filePath = ltrim(str_replace('repo/', '', $processedData['research_proposal']), '/');
                                    $segments = explode('/', $filePath);
                                    $fileName = array_pop($segments);
                                    $folder = implode('/', $segments);
                                    
                                    $encodedFileName = urlencode($fileName);
                                    $encodedFolder = urlencode($folder);
                                @endphp
        
                                <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                                    target="_blank" class="btn btn-primary">
                                    View / Download Research Proposal
                                </a>
                            </div>
                        @else
                            <input class="form-control" type="file" id="research_proposal" name="research_proposal" accept=".pdf" onchange="validateFileSize(this)">
                        @endif
        
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="outcome">Expected Research Outcomes <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="outcome" name="outcome" 
                               value="{{ old('outcome', $processedData['outcome'] ?? '') }}" 
                               placeholder="e.g., Logbook, etc.">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="supervisor">Preferred Supervisor in Universitas Airlangga (if any) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="supervisor" name="supervisor" 
                               value="{{ old('supervisor', $processedData['supervisor'] ?? '') }}" 
                               placeholder="supervisor in Universitas Airlangga">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <!-- Starting Date of Research Study -->
                    <div class="mb-3">
                        <label class="form-label" for="start_date_prog">Starting Date of Research Study <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="start_date_prog" name="start_date_prog" 
                            value="{{ old('start_date_prog', $processedData['start_date_prog'] ?? '') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- End Date of Research Study -->
                    <div class="mb-3">
                        <label class="form-label" for="end_date_prog">End Date of Research Study <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="end_date_prog" name="end_date_prog" 
                            value="{{ old('end_date_prog', $processedData['end_date_prog'] ?? '') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>

        @elseif($processedData['selected_program'] === 'Lingua' || $processedData['selected_program'] === '-')

            {{-- CHOICE OF SUBJECT | LINGUA REGULAR --}}
            @if($processedData['type'] === 'lingua')
                <h5 class="mt-3">Choice of Subjects</h5>
                <hr>
                <div class="mb-3 mt-3 d-flex justify-content-between align-content-center">
                    <label class="form-label d-block">
                        Have you ever taken Bahasa Indonesia course? <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex align-items-center">
                        <input type="hidden" name="taken_indo" value="No">
                        <label class="switch me-2">
                            <input type="checkbox" id="taken_indo_checkbox" name="taken_indo" value="yes" 
                                {{ old('taken_indo', $processedData['taken_indo'] ?? 'No') == 'yes' ? 'checked' : '' }} 
                                onchange="toggleStatus('taken_indo_checkbox', 'taken_indo_status')">
                            <span class="switch-state"></span>
                        </label>
                        <span id="taken_indo_status">{{ old('taken_indo', $processedData['taken_indo'] ?? 'No') == 'yes' ? 'Yes' : 'No' }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="joined_lingua">Have you previously joined Lingua <span class="text-danger">*</span></label>
                    <select class="form-select" id="joined_lingua" name="joined_lingua">
                        <option value="">Select</option>
                        <option value="a1" {{ old('joined_lingua', $processedData['joined_lingua'] ?? '') == 'a1' ? 'selected' : '' }}>
                            Yes, I joined LINGUA A1 class last semester
                        </option>
                        <option value="a2" {{ old('joined_lingua', $processedData['joined_lingua'] ?? '') == 'a2' ? 'selected' : '' }}>
                            Yes, I joined LINGUA A2 class last semester
                        </option>
                        <option value="b1" {{ old('joined_lingua', $processedData['joined_lingua'] ?? '') == 'b1' ? 'selected' : '' }}>
                            Yes, I joined LINGUA B1 class last semester
                        </option>
                        <option value="b2" {{ old('joined_lingua', $processedData['joined_lingua'] ?? '') == 'b2' ? 'selected' : '' }}>
                            Yes, I joined LINGUA B2 class last semester
                        </option>
                        <option value="no" {{ old('joined_lingua', $processedData['joined_lingua'] ?? '') == 'no' ? 'selected' : '' }}>
                            No, I have not
                        </option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @endif

        @endif
        
        {{-- ATTACHMENT FILE --}}
        <h5 class="mt-6">Attachment File</h5>
        <hr>

        <div class="row">
            <div class="col-md-6"> 

                <div class="mb-3">
                    <label class="form-label" for="passport">Passport Identity Page (PDF) <span class="text-danger">*</span></label>
                    
                    @if(isset($processedData) && !empty($processedData['passport'] ))
                        <input class="form-control" type="file" id="passport" name="passport" accept=".pdf" onchange="validateFileSize(this)">
                        <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                        <div class="mt-2">
                            @php
                                $filePath = ltrim(str_replace('repo/', '', $processedData['passport'] ), '/');
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
                        <input class="form-control" type="file" id="passport" name="passport" accept=".pdf" onchange="validateFileSize(this)">
                    @endif
                    
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="transcript">Official academic transcript in English (issued by the applicant's home institution)</label>
                    
                    @if(isset($processedData) && !empty($processedData['transcript']))
                        <input class="form-control" type="file" id="transcript" name="transcript" accept=".pdf" onchange="validateFileSize(this)">
                        <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                        <div class="mt-2">
                            @php
                                $filePath = ltrim(str_replace('repo/', '', $processedData['transcript']), '/');
                                $segments = explode('/', $filePath);
                                $fileName = array_pop($segments);
                                $folder = implode('/', $segments);
                                
                                $encodedFileName = urlencode($fileName);
                                $encodedFolder = urlencode($folder);
                            @endphp

                            <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                                target="_blank" class="btn btn-primary">
                                View / Download Transcript
                            </a>
                        </div>
                    @else
                        <input class="form-control" type="file" id="transcript" name="transcript" accept=".pdf" onchange="validateFileSize(this)">
                    @endif
                    
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="photo">Most recent passport size photo (red/blue/white background) <span class="text-danger">*</span></label>

                    @if(isset($processedData) && !empty($processedData['photo']))
                        <input class="form-control" type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png"
                            onchange="handleFileChange(event, 'photoPreviewDiv', 'photoPreview', 240)">
                        <div class="form-text">Upload a new file to replace the existing photo (optional).</div>
                    @else
                        <input class="form-control" type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png" 
                            onchange="handleFileChange(event, 'photoPreviewDiv', 'photoPreview', 240)">
                    @endif

                    <div class="invalid-feedback"></div>
                </div>

                <!-- Photo Preview -->
                <div class="mb-3">
                    <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="photoPreviewDiv">
                        <img 
                            id="photoPreview" 
                            src="{{ isset($processedData) && !empty( $processedData['photo']) ?  $processedData['photo'] : '' }}" 
                            alt="{{ isset($processedData) && !empty( $processedData['photo']) ? 'Photo Preview' : '' }}" 
                            class="img-fluid" 
                            style="{{ isset($processedData) && !empty( $processedData['photo']) ? 'height: 240px; object-fit: cover;' : 'display: none;' }}">
                    </div>
                </div>
            </div>  
            
            <div class="col-md-6">
               
                <div class="mb-3">
                    <label class="form-label" for="letter_recom">One (1) Professional Letter of Recommendation signed by the referee 
                        (preferably from a professor, invalid if recommendations are made by family members or friends)
                    </label>

                    @if(isset($processedData) && !empty($processedData['letter_recom']))
                        <input class="form-control" type="file" id="letter_recom" name="letter_recom" accept=".pdf" onchange="validateFileSize(this)">
                        <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                        <div class="mt-2">
                            @php
                                $filePath = ltrim(str_replace('repo/', '', $processedData['letter_recom']), '/');
                                $segments = explode('/', $filePath);
                                $fileName = array_pop($segments);
                                $folder = implode('/', $segments);
                                
                                $encodedFileName = urlencode($fileName);
                                $encodedFolder = urlencode($folder);
                            @endphp

                            <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                                target="_blank" class="btn btn-primary">
                                View / Download Letter of Recommendation
                            </a>
                        </div>
                    @else
                        <input class="form-control" type="file" id="letter_recom" name="letter_recom" accept=".pdf" onchange="validateFileSize(this)">
                    @endif

                    <div class="invalid-feedback"></div>
                </div>

                
                <div class="mb-3">
                    <label class="form-label" for="english_certificate">Scanned English Proficiency Certificate <span class="text-danger">*</span></label>

                    @if(isset($processedData) && !empty($processedData['english_certificate'] ))
                        <input class="form-control" type="file" id="english_certificate" name="english_certificate" accept=".pdf" onchange="validateFileSize(this)">
                        <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                        <div class="mt-2">
                            @php
                                $filePath = ltrim(str_replace('repo/', '', $processedData['english_certificate'] ), '/');
                                $segments = explode('/', $filePath);
                                $fileName = array_pop($segments);
                                $folder = implode('/', $segments);
                                
                                $encodedFileName = urlencode($fileName);
                                $encodedFolder = urlencode($folder);
                            @endphp

                            <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                                target="_blank" class="btn btn-primary">
                                View / Download English Certificate
                            </a>
                        </div>
                    @else
                        <input class="form-control" type="file" id="english_certificate" name="english_certificate" accept=".pdf" onchange="validateFileSize(this)">
                    @endif

                    <div class="invalid-feedback"></div>
                </div>

                
                <div class="mb-3">
                    <label class="form-label" for="cv">Curriculum Vitae <span class="text-danger">*</span></label>

                    @if(isset($processedData) && !empty($processedData['cv']))
                        <input class="form-control" type="file" id="cv" name="cv" accept=".pdf" onchange="validateFileSize(this)">
                        <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                        <div class="mt-2">
                            @php
                                $filePath = ltrim(str_replace('repo/', '', $processedData['cv']), '/');
                                $segments = explode('/', $filePath);
                                $fileName = array_pop($segments);
                                $folder = implode('/', $segments);
                                
                                $encodedFileName = urlencode($fileName);
                                $encodedFolder = urlencode($folder);
                            @endphp

                            <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                                target="_blank" class="btn btn-primary">
                                View / Download CV
                            </a>
                        </div>
                    @else
                        <input class="form-control" type="file" id="cv" name="cv" accept=".pdf" onchange="validateFileSize(this)">
                    @endif

                    <div class="invalid-feedback"></div>
                </div>

                
                <div class="mb-3">
                    <label class="form-label" for="motivation_letter">Motivation Letter</label>

                    @if(isset($processedData) && !empty($processedData['motivation_letter']))
                        <input class="form-control" type="file" id="motivation_letter" name="motivation_letter" accept=".pdf" onchange="validateFileSize(this)">
                        <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                        <div class="mt-2">
                            @php
                                $filePath = ltrim(str_replace('repo/', '', $processedData['motivation_letter']), '/');
                                $segments = explode('/', $filePath);
                                $fileName = array_pop($segments);
                                $folder = implode('/', $segments);
                                
                                $encodedFileName = urlencode($fileName);
                                $encodedFolder = urlencode($folder);
                            @endphp

                            <a href="{{ route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) }}" 
                                target="_blank" class="btn btn-primary">
                                View / Download Motivation Letter
                            </a>
                        </div>
                    @else
                        <input class="form-control" type="file" id="motivation_letter" name="motivation_letter" accept=".pdf" onchange="validateFileSize(this)">
                    @endif

                    <div class="invalid-feedback"></div>
                </div>

            </div>
        </div>
            
        <!-- Hidden Submit Button -->
        <button type="submit" id="submitForm" class="d-none"></button>
    </form>
  </div>
</div>
@endsection

<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
 
    document.getElementById('confirmUpdate').addEventListener('click', function () {
        if (validateForm()) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update the data?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('submitForm').click(); // Trigger form submission
                }
            });
        }
    });

    document.getElementById('submitForm').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default submit

        const form = document.getElementById('updateForm');
        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(response => {
            if (response.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: `Data has been updated.`,
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
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: error.message || 'An error occurred while processing the data.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
    let participantId = "{{ isset($processedData['id']) ? $processedData['id'] : '' }}";

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
                    sendRequest("{{ route('approve_peserta_inbound', ['id' => $processedData['id'] ]) }}", "PUT", {}, "Participant approved successfully!");
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
                      sendRequest("{{ route('unapprove_peserta_inbound', ['id' => $processedData['id'] ])  }}", "PUT", {}, "Participant approval has been removed.");
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
                      sendRequest("{{ route('reject_peserta_inbound', ['id' => $processedData['id'] ]) }}", "PUT", {}, "Participant has been rejected.");
                  }
              });
          });
     
});

function validateForm() {
    let isValid = true;
    let requiredFields = document.querySelectorAll('input[required], textarea[required], select[required]'); // Tangani semua required fields
    let requiredFiles = document.querySelectorAll('input[type="file"][required]'); // Tangani file input yang required

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add("is-invalid");

            let feedback = field.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.textContent = "This field is required.";
                feedback.style.display = "block";
            }
        } else {
            field.classList.remove("is-invalid");

            let feedback = field.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.style.display = "none";
            }
        }
    });

    // Validasi khusus untuk file upload
    requiredFiles.forEach(fileInput => {
        if (fileInput.files.length === 0) {
            isValid = false;
            fileInput.classList.add("is-invalid");

            let feedback = fileInput.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.textContent = "You must upload this file.";
                feedback.style.display = "block";
            }
        } else {
            fileInput.classList.remove("is-invalid");
            let feedback = fileInput.closest(".mb-3").querySelector(".invalid-feedback");
            if (feedback) {
                feedback.style.display = "none";
            }
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields before submitting.',
            confirmButtonColor: '#d33'
        });
    }

    return isValid;
}
   
    // SweetAlert for success or error messages from session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif
</script>

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
  