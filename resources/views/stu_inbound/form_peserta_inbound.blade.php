@extends('layouts.master')

@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Bagian Kiri: Judul dan Span -->
      <div>
          <h5 class="mb-0">Your Title Here</h5>
          <span>This is Optional Notes</span>
      </div>

      <!-- Bagian Kanan: Tombol -->
      <div class="d-flex row">
      </div>
    </div>
    <hr>
  </div>
  <div class="card-body">
    <form class="was-validated" action="{{ route('program_stuin.store') }}" method="post" enctype="multipart/form-data">
      @csrf

        <!-- Type -->
        <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select class="form-select" id="type" name="type" disabled>
                <option value="amerta" {{ old('type', $processedData['type'] ?? '') === 'amerta' ? 'selected' : '' }}>Amerta</option>
                <option value="lingua" {{ old('type', $processedData['type'] ?? '') === 'lingua' ? 'selected' : '' }}>Lingua</option>
            </select>
            <div class="invalid-feedback">Type wajib dipilih.</div>
        </div>

        <!-- Selected Program -->
        <div class="mb-3">
            <label class="form-label" for="sel_prog">Selected Program</label>
            <select class="form-select" id="sel_prog" name="sel_prog" disabled>
                <option value="amerta" {{ old('sel_prog', $processedData['selected_program'] ?? '') === 'amerta' ? 'selected' : '' }}>Amerta</option>
                <option value="lingua" {{ old('sel_prog', $processedData['selected_program'] ?? '') === '-' ? 'selected' : '' }}>Lingua</option>
            </select>
            <div class="invalid-feedback">Selected Program wajib dipilih.</div>
        </div>

        <!-- Intended Mobility -->
        <div class="mb-3">
            <label class="form-label" for="mobility">Intended Mobility</label>
            <select class="form-select" id="mobility" name="mobility" disabled>
                <option value="vm" {{ old('mobility', $processedData['mobility'] ?? '') === 'vm' ? 'selected' : '' }}>Virtual Mobility</option>
                <option value="m" {{ old('mobility', $processedData['mobility'] ?? '') === 'm' ? 'selected' : '' }}>Mobility</option>
            </select>
            <div class="invalid-feedback">Mobility wajib dipilih.</div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <textarea class="form-control" id="email" name="email" readonly>{{ old('email', $processedData['email'] ?? '') }}</textarea>
            <div class="invalid-feedback">Email wajib diisi.</div>
        </div>

        <!-- Secondary Email -->
        <div class="mb-3">
            <label class="form-label" for="secondary_email">Secondary Email</label>
            <textarea class="form-control" id="secondary_email" name="secondary_email" readonly>{{ old('secondary_email', $processedData['secondary_email'] ?? '') }}</textarea>
            <div class="invalid-feedback">Email wajib diisi.</div>
        </div>
    </form>
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
        @elseif($processedData['selected_program'] === 'Lingua' || $processedData['selected_program'] === '-')
            <h5 class="mb-0">Lingua</h5>
            <span>The personal information of the student</span>
        @endif


      </div>

      <!-- Bagian Kanan: Tombol -->
      <div class="d-flex row">
       
      </div>
    </div>
    <hr>
  </div>
  <div class="card-body">
    <form class="was-validated" action="{{ route('program_stuin.store') }}" method="post" enctype="multipart/form-data">
      @csrf

      <h5 class="mt-3">Personal Information</h5>
      <hr>
        <div class="row">
            <div class="col-md-6">
                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label" for="fullname">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="e.g: Rizal Akbar Johar" value="{{ old('email', $processedData['fullname'] ?? '') }}" required>
                    <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                </div>

                <!-- First Name -->
                <div class="mb-3">
                    <label class="form-label" for="firstname">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="e.g: Rizal Akbar"  value="{{ old('firstname', $processedData['firstname'] ?? '') }}" required>
                    <div class="invalid-feedback">Nama depan wajib diisi.</div>
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label class="form-label" for="lastname">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="e.g: Johar"  value="{{ old('lastname', $processedData['lastname'] ?? '') }}" required>
                    <div class="invalid-feedback">Nama belakang wajib diisi.</div>
                </div>

                <!-- Sex -->
                <div class="mb-3">
                    <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                    <select class="form-select" id="sex" name="sex" required>
                        <option value="M" {{ old('sex', $processedData['sex'] ?? '') === 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('sex', $processedData['sex'] ?? '') === 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                    <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                </div>

                <!-- Place of Birth -->
                <div class="mb-3">
                    <label class="form-label" for="pob">Place of Birth <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pob" name="pob" placeholder="e.g: Surabaya, Indonesia" value="{{ old('pob', $processedData['pob'] ?? '') }}" required>
                    <div class="invalid-feedback">Tempat lahir wajib diisi.</div>
                </div>

                <!-- Date of Birth -->
                <div class="mb-3">
                    <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $processedData['dob'] ?? '') }}" required>
                    <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                </div>  
                    
                <!-- Nationality -->
                <div class="mb-3">
                    <label class="form-label" for="nationality">Nationality <span class="text-danger">*</span></label>
                    <select class="form-select js-example-basic-single" id="nationality" name="nationality" required>
                        @foreach($country as $item)
                            <option value="{{ $item->id }}" {{ old('nationality', $processedData['nationality'] ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>                        
                    <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                </div>

                <!-- Telephone Number -->
                <div class="mb-3">
                    <label class="form-label" for="telephone">Telephone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="e.g: +628 1234567" value="{{ old('telephone', $processedData['telephone'] ?? '') }}" required>
                    <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                </div>

                <!-- Mobile/Cellular Number -->
                <div class="mb-3">
                    <label class="form-label" for="phone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g: +628 1234567" value="{{ old('phone', $processedData['phone'] ?? '') }}" required>
                    <div class="invalid-feedback">Nomor ponsel wajib diisi.</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Passport Number -->
                <div class="mb-3">
                    <label class="form-label" for="passport_number">Passport Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="passport_number" name="passport_number" placeholder="Enter your Passport number here" value="{{ old('passport_number', $processedData['passport_number'] ?? '') }}" required>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Passport Date of Issue -->
                <div class="mb-3">
                    <label class="form-label" for="passport_date_issue">Passport Date of Issue <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="passport_date_issue" name="passport_date_issue" value="{{ old('passport_date_issue', $processedData['passport_date_issue'] ?? '') }}" required>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Passport Date of Expiration -->
                <div class="mb-3">
                    <label class="form-label" for="passport_date_exp">Passport Date of Expiration <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="passport_date_exp" name="passport_date_exp" value="{{ old('passport_date_exp', $processedData['passport_date_exp'] ?? '') }}" required>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Issuing Authority -->
                <div class="mb-3">
                    <label class="form-label" for="issuing_authority">Issuing Authority <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="issuing_authority" name="issuing_authority" placeholder="e.g: Immigration Office of Surabaya, Indonesia" value="{{ old('issuing_authority', $processedData['issuing_authority'] ?? '') }}" required>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Home Address -->
                <div class="mb-3">
                    <label class="form-label" for="address">Home Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="address" name="address" placeholder="Please provide the details such as region, province, 
                    country, and postal code" rows="2" required>{{ old('address', $processedData['address'] ?? '') }}</textarea>
                    <div class="invalid-feedback">Alamat rumah wajib diisi.</div>
                </div>

                <!-- Mailing Address -->
                <div class="mb-3">
                    <label class="form-label" for="mail_address">Mailing Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="mail_address" name="mail_address" placeholder="Please provide the details such as region, 
                    province, country, and postal code" rows="2" required>{{ old('mail_address', $processedData['mail_address'] ?? '') }}</textarea>
                    <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                </div>

                <!-- Indonesian Embassy Address -->
                <div class="mb-3">
                    <label class="form-label" for="embassy_address">Indonesian Embassy Address</label>
                    <textarea class="form-control" id="embassy_address" name="embassy_address" placeholder="Please provide the details of the nearest indonesian 
                    embaassy in your respective country, e.g. The embassy of republic indonesia, 233. Jalan Tun Razak, Imbi, 50400 Kuala Lumpur wilayah" 
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
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="degree">Degree Being Taken at University <span class="text-danger">*</span></label>
                    <select class="form-select" id="degree" name="degree" required>
                        <option value="">Select Degree</option>
                        <option value="Undergraduate">Undergraduate</option>
                        <option value="Master">Master</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
        
                <div class="mb-3">
                    <label class="form-label" for="major">Major <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="major" name="major" placeholder="Biotechnology, etc." required>
                    <div class="invalid-feedback">Program studi wajib diisi.</div>
                </div>
                
            </div>
            
            <div class="col-md-6">
                
                @if($processedData['type'] === 'amerta')
                    <div class="mb-3">
                        <label class="form-label" for="gpa">GPA <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="gpa" name="gpa" placeholder="Your GPA/ Max GPA (e.g: 3.3/4.0 or 4.1/55.0)" required>
                        <div class="invalid-feedback">GPA wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="year_entry">Year of Entry <span class="text-danger">*</span></label>
                        <input type="month" class="form-control" id="year_entry" name="year_entry" required>
                        <div class="invalid-feedback">Tahun masuk wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="native">Native Language <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="native" name="native" placeholder="English, Indonesian" required>
                        <div class="invalid-feedback">Bahasa asli wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="english_score">English Proficiency Score *</label>
                        <input type="text" class="form-control" id="english_score" name="english_score" placeholder="Your GPA/ Max GPA (e.g: 3.3/4.0 or 4.1/55.0)">
                        <div class="invalid-feedback">Nilai Bahasa Inggris wajib diisi.</div>
                    </div>
                @endif
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <h5 class="mt-3">Particular Next of Kin</h5>
                <hr>

                <div class="mb-3">
                    <label class="form-label" for="kin_name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="kin_name" name="kin_name" placeholder="e.g: Adi Johan" required>
                    <div class="invalid-feedback">Nama wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_relation">Relationship <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="kin_relation" name="kin_relation" placeholder="e.g: Father" required>
                    <div class="invalid-feedback">Hubungan wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_address">Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="kin_address" name="kin_address" placeholder="Please provide the details such as region, province, country, and postal code" rows="2" required></textarea>
                    <div class="invalid-feedback">Alamat wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="kin_email" name="kin_email" placeholder="Email Kerabat" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_telephone">Telephone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="kin_telephone" name="kin_telephone" placeholder="e.g: +628 1234567" required>
                    <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kin_homephone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="kin_homephone" name="kin_homephone" placeholder="e.g: +628 1234567">
                </div>
            </div>
            
            <div class="col-md-6">

                <h5 class="mt-3">Details of The Contact Person at Home University</h5>
                <hr>

                <div class="mb-3">
                    <label class="form-label" for="pic_name">Contact Person at your Home University <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pic_name" name="pic_name" placeholder="e.g: Adi Johan" required>
                    <div class="invalid-feedback">Kontak wajib diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_position">Position of the Contact Person at your Home University <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pic_position" name="pic_position" placeholder="e.g: Head of International Office" required>
                    <div class="invalid-feedback">Jabatan wajib diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="pic_email" name="pic_email" placeholder="" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_telephone">Telephone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="pic_telephone" name="pic_telephone" placeholder="e.g: +628 1234567" required>
                    <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pic_address">Mailing Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="pic_address" name="pic_address" placeholder="For Transcript and Certificate shipping" rows="2" required></textarea>
                    <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                </div>

            </div>
        </div>

        <!-- Character Reference -->
        @if($processedData['type'] === 'amerta')
            <h5 class="mt-3">Character Reference</h5>
            <hr>
            <div class="mb-3">
                <label class="form-label" for="referee_name">Name of Referee (as it appears in Identity Card / Passport and BLOCK CAPSE Surname / Family / Paternal Name) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="referee_name" name="referee_name" placeholder="e.g: Adi Johan" required>
                <div class="invalid-feedback">Nama rujukan wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="referee_organization">Organization and Position <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="referee_organization" name="referee_organization" placeholder="e.g: Father" required>
                <div class="invalid-feedback">Organisasi dan jabatan wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="referee_relation">Relationship to Applicant <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="referee_relation" name="referee_relation" placeholder="e.g: Father" required>
                <div class="invalid-feedback">Hubungan dengan rujukan wajib diisi.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="referee_email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="referee_email" name="referee_email" placeholder="Masukkan Email Rujukan" required>
                <div class="invalid-feedback">Email rujukan wajib diisi.</div>
            </div>
        @endif


        {{-- PERPECAHAN SESUAI SELECTED PROGRAM --}}
        @if($processedData['selected_program'] === 'Amerta Regular' || $processedData['selected_program'] === 'AMERTA')

            {{-- CHOICE OF SUBJECT | AMERTA REGULAR --}}
            @if($type === 'amerta')
                <div class="amerta-regular-form">
                    <h5 class="mt-3">Choice of Subjects</h5>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label" for="course1">Course 1 <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single col-sm-12" id="course1" name="course1" required>
                            <option value="" selected>Select Course</option>
                            @foreach($course as $item)
                                <option value="{{ $item->code }}">{{ $item->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    @for ($i = 2; $i <= 6; $i++)
                        <div class="mb-3">
                            <label class="form-label" for="course{{ $i }}">Course{{ $i }}</label>
                            <select class="js-example-basic-single col-sm-12" id="course{{ $i }}" name="course[{{ $i }}]">
                                <option value="" selected>Select Course</option>
                                @foreach($course as $item)
                                    <option value="{{ $item->code }}">{{ $item->title}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    @endfor

                    <div class="mb-3 mt-3 d-flex justify-content-between align-content-center">
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
                    </div>
                    
                    <div class="mb-3 d-flex justify-content-between align-content-center">
                        <label class="form-label d-block">
                            Taking Bahasa Indonesia Course as Complementary Course? <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="take_indo" value="No">
                            <label class="switch me-2">
                                <input type="checkbox" id="take_indo_checkbox" name="take_indo" value="yes" onchange="toggleStatus('take_indo_checkbox', 'take_indo_status')">
                                <span class="switch-state"></span>
                            </label>
                            <span id="take_indo_status">No</span>
                        </div>
                    </div>
                </div>
            @endif

        @elseif($processedData['selected_program'] === 'Amerta Internship')
        
        <!-- SECTION FOR AMERTA INTERNSHIP -->
        <div class="amerta-internship-form">
            <h5 class="mt-3">Internship Field</h5>
            <hr>
            <div class="mb-3">
                <label class="form-label" for="field_of_study">Field of Study</label>
                <input type="text" class="form-control" id="field_of_study" name="field_of_study" placeholder="AMERTA Internship Only">
                <div class="invalid-feedback"></div>
            </div>
        </div>


        @elseif($processedData['selected_program'] === 'Amerta Research')

        <!-- ATTACHMENT AMERTA RESEARCH -->
        <div class="amerta-research-form">
            <h5 class="mt-3">Research Field</h5>
            <hr>
            <div class="mb-3">
                <label class="form-label" for="research_proposal">Research Proposal</label>
                <input class="form-control" type="file" id="research_proposal" name="research_proposal" accept=".pdf" required>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        

        @elseif($processedData['selected_program'] === 'Lingua' || $processedData['selected_program'] === '-')

            {{-- CHOICE OF SUBJECT | LINGUA REGULAR --}}
            @if($type === 'lingua')
                <h5 class="mt-3">Choice of Subjects</h5>
                <hr>
                <div class="mb-3 mt-3 d-flex justify-content-between align-content-center">
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
                </div>

                <div class="mb-3">
                    <label class="form-label" for="joined_lingua">Have you previously joined Lingua <span class="text-danger">*</span></label>
                    <select class="form-select" id="joined_lingua" name="joined_lingua" required>
                        <option value="">Select /option>
                        <option value="a1">Yes, I joined LINGUA A1 class last semester</option>
                        <option value="a2">Yes, I joined LINGUA A2 class last semester</option>
                        <option value="b1">Yes, I joined LINGUA B1 class last semester</option>
                        <option value="b2">Yes, I joined LINGUA B2 class last semester</option>
                        <option value="no">No, I have not</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @endif

        @endif

        @if($processedData['selected_program'] === 'Amerta Regular' || $processedData['selected_program'] === 'AMERTA' || $processedData['selected_program'] === 'Lingua' || $processedData['selected_program'] === '-')

            <!-- ATTACHMENT FOR AMERTA AND LINGUA -->
            <div class="amerta-regular-form"> 
                <h5 class="mt-6">Attachment File</h5>
                <hr>
                <div class="mb-3">
                    <label class="form-label" for="passport">Passport Identity Page (PDF) <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="passport" name="passport" accept=".pdf" required>
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="photo">Most recent passport size photo(red/blue/white background) <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png" required>
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="transcript">Official academic transcript in English (issued by the applicant''s home institution)</label>
                    <input class="form-control" type="file" id="transcript" name="transcript" accept=".pdf">
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="letter_recom_referee">One (1) Professional Letter of Recomendation signed by the referee (preferably from a professor, invalid if recommendations are made by family members or friends)</label>
                    <input class="form-control" type="file" id="letter_recom_referee" name="letter_recom_referee" accept=".pdf">
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="cv">Curriculum Vitae <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="cv" name="cv" accept=".pdf" required>
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="motivation_letter">Motivation Letter <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="motivation_letter" name="motivation_letter" accept=".pdf" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

        @endif
    
    </form>
  </div>
</div>
@endsection



