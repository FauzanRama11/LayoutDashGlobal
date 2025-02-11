@extends('pendaftaran.master')

@section('content') 
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
    });
</script>
@endif

<div class="page-body px-4 py-5">
<div class="container-fluid">
    <div class="row">
      <div class="col-md-5 d-flex justify-content-center align-items-center" >
        @if($type === 'amerta')
            <img src="{{ asset('assets/images/LogoAmerta.png') }}" alt="Logo Amerta" class="img-fluid"  style="width: 320px; height: auto;">
        @else
            <img src="{{ asset('assets/images/LogoLingua.png') }}" alt="Logo Lingua" class="img-fluid"  style="width: 320px; height: auto;">
        @endif
      </div>

      <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5>{{ $title }}</h5>
                <span>Please Make sure fill all the filed before click on next button</span>
            </div>
            <div class="card-body">
            
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a class="btn btn-primary" href="#step-1">1</a>
                        <p>Step 1</p>
                    </div>
                    <div class="stepwizard-step">
                        <a class="btn btn-light" href="#step-2">2</a>
                        <p>Step 2</p>
                    </div>
                    <div class="stepwizard-step">
                        <a class="btn btn-light" href="#step-3">3</a>
                        <p>Step 3</p>
                    </div>
                </div>
            </div>
              
            <form class="form-wizard" id="regForm" action="{{ route('simpan.registrasi', ['type' => $type ]) }}" method="POST" enctype="multipart/form-data">
            @csrf

                <!-- Step 1 -->
                <div id="step-1" class="tab setup-content">
                    <input type="hidden" name="step" value="1">

                    <div class="title">INSTRUCTION</div>
                        <ol>
                            <li>This Application Form must be fully completed in the required format.</li>
                            <li>Please indicate "NA" if an item is not applicable.</li>
                            <li>The Application Form consists of two sections:
                                <ul>
                                <li>Personal Information</li>
                                <li>Particulars of Next-of-Kin</li>
                                <li>Educational Qualifications</li>
                                <li>Details of The Contact Person at Home University</li>
                                <li>Program fields</li>
                                <li>Document Attachments</li>
                                </ul>
                            </li>
                        </ol>
                        <br>
                        
                        <p>Please upload the required supporting documents along with this application form:</p>
                        <ol>
                            <li>Paaport Identity Page</li>
                            <li>Most recent passport-size photo (red/blue/white background)</li>
                            <li>Recommendation Letter by Home University</li>
                            <li>Official academic transcript in English</li>
                            <li>Scanned English Proficiency Certificate</li>
                            <li>Curriculum Vitae (CV)</li>
                            <li>Motivation Letter</li>
                        </ol>
                        <br>

                    <div class="title">NOTE</div>
                    <p>
                        This application will only be processed if all required supporting documents are submitted.
                        <br>
                        For any inquiries, please do not hesitate to contact us via email:
                    <br>
                        <a href="mailto:inbound@global.unair.co.id" style="color:blue;">
                            <i class="fa fa-envelope-o" style="margin-right:5px; color: #222; text-decoration:none;"></i>inbound@global.unair.co.id
                        </a>
                    </p>
                    <br>
                </div>
              
                <!-- Step 2 -->
                <div id="step-2" class="tab setup-content">
                    <input type="hidden" name="step" value="2">
                    <input type="hidden" name="mobility" value="vm">

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Secondary Email -->
                    <div class="mb-3">
                        <label class="form-label" for="secondary_email">Secondary Email</label>
                        <input  type="email" class="form-control" id="secondary_email" name="secondary_email">
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    @if($type === 'amerta')
                        <!-- Dropdown -->
                        <div class="mb-3">
                            <label class="form-label" for="selected_program">Selected Program <span class="text-danger">*</span></label>
                            <select class="form-select" id="selected_program" name="selected_program" required>
                                <option value="">Selected Program</option>
                                <option value="AMERTA">Amerta Regular</option>
                                <option value="Amerta Internship">Amerta Internship</option>
                                <option value="Amerta Research">Amerta Research</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    @endif
                </div>

              
                <!-- Step 3 -->
                <div id="step-3" class="tab setup-content">
                    <input type="hidden" name="step" value="3">
                    
                    <h5 class="mt-3">Personal Information</h5>
                    <hr>
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label" for="fullname">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="e.g: Rizal Akbar Johar" required>
                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                    </div>

                    <!-- First Name -->
                    <div class="mb-3">
                        <label class="form-label" for="firstname">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="e.g: Rizal Akbar" required>
                        <div class="invalid-feedback">Nama depan wajib diisi.</div>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label class="form-label" for="lastname">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="e.g: Johar" required>
                        <div class="invalid-feedback">Nama belakang wajib diisi.</div>
                    </div>

                    <!-- Sex -->
                    <div class="mb-3">
                        <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                        <select class="form-select" id="sex" name="sex" required>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                        <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                    </div>

                    <!-- Place of Birth -->
                    <div class="mb-3">
                        <label class="form-label" for="pob">Place of Birth <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pob" name="pob" placeholder="e.g: Surabaya, Indonesia" required>
                        <div class="invalid-feedback">Tempat lahir wajib diisi.</div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-3">
                        <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                        <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                    </div>

                    <!-- Nationality -->
                    <div class="mb-3">
                        <label class="form-label" for="nationality">Nationality <span class="text-danger">*</span></label>
                        <select class="form-select js-example-basic-single" id="nationality" name="nationality" required>
                            @foreach($country as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                    </div>

                    <!-- Passport Number -->
                    <div class="mb-3">
                        <label class="form-label" for="passport_number">Passport Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="passport_number" name="passport_number" placeholder="Enter your Passport nummber here" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Passport Date of Issue -->
                    <div class="mb-3">
                        <label class="form-label" for="passport_date_issue">Passport Date of Issue <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="passport_date_issue" name="passport_date_issue" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Passport Date of Expiration -->
                    <div class="mb-3">
                        <label class="form-label" for="passport_date_exp">Passport Date of Expiration <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="passport_date_exp" name="passport_date_exp" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Issuing Authority -->
                    <div class="mb-3">
                        <label class="form-label" for="issuing_authority">Issuing Authority <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="issuing_authority" name="issuing_authority" placeholder="e.g: Immigration Office of Surabaya, Indonesia" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Telephone Number -->
                    <div class="mb-3">
                        <label class="form-label" for="telephone">Telephone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="e.g: +628 1234567" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                    </div>

                    <!-- Mobile/Cellular Number -->
                    <div class="mb-3">
                        <label class="form-label" for="phone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g: +628 1234567" required>
                        <div class="invalid-feedback">Nomor ponsel wajib diisi.</div>
                    </div>

                    <!-- Home Address -->
                    <div class="mb-3">
                        <label class="form-label" for="address">Home Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" name="address" placeholder="Please provide the details such as region, province, country, and postal code" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat rumah wajib diisi.</div>
                    </div>

                    <!-- Mailing Address -->
                    <div class="mb-3">
                        <label class="form-label" for="mail_address">Mailing Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="mail_address" name="mail_address" placeholder="Please provide the details such as region, province, country, and postal code" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                    </div>

                    <!-- Indonesian Embassy Address -->
                    <div class="mb-3">
                        <label class="form-label" for="embassy_address">Indonesian Embassy Address</label>
                        <textarea class="form-control" id="embassy_address" name="embassy_address" placeholder="Please provide the details of the nearest indonesian embaassy in your respective country, e.g. The embassy of republic indonesia, 233. Jalan Tun Razak, Imbi, 50400 Kuala Lumpur wilayah" rows="2"></textarea>
                    </div>

                    <!-- Particular Next of Kin -->
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
                        <label class="form-label" for="kin_phone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="kin_phone" name="kin_phone" placeholder="e.g: +628 1234567">
                    </div>
        
                    <!-- Educational Qualification -->
                    <h5 class="mt-3">Educational Qualification</h5>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label" for="university">Home University (non-Indonesian University) <span class="text-danger">*</span></label>
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
                            <option value="Bachelor">Bachelor</option>
                            <option value="Master">Master</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="faculty">Faculty (Home University) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="faculty" name="faculty" placeholder="Science, etc." required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="major">Major (Home University) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="major" name="major" placeholder="Biotechnology, etc." required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    @if($type === 'amerta')
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
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="native">Medium of Instruction in Home University <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="native" name="native" placeholder="English, Indonesian" required>
                        <div class="invalid-feedback">Bahasa asli wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="english_score">English Proficiency Score <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="english_score" name="english_score" placeholder="Your GPA/ Max GPA (e.g: 3.3/4.0 or 4.1/55.0)">
                        <div class="invalid-feedback">Nilai Bahasa Inggris wajib diisi.</div>
                    </div>
        
                    <!-- Character Reference -->
                    @if($type === 'amerta')
                        <h5 class="mt-3">Character Reference</h5>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label" for="referee_name">Name of Referee (with Salutation) <span class="text-danger">*</span></label>
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
        
                    <!-- Contact Person at Home University -->
                    <h5 class="mt-3">Details of The Contact Person at Home University</h5>
                    <div class="mb-3">
                        <label class="form-label" for="pic_name">Name of the Contact Person <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pic_name" name="pic_name" placeholder="e.g: Adi Johan" required>
                        <div class="invalid-feedback">Kontak wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pic_position">Position of the Contact Person at Home University <span class="text-danger">*</span></label>
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

                    {{-- SEPERATED BASED ON EACH CATEGORY --}}

                    {{-- CHOICE OF SUBJECT | AMERTA REGULAR --}}
                    @if($type === 'amerta')
                        <div class="amerta-regular-form" style="display: none;">

                            <input type="hidden" name="progCategory" value="Semester Program">
                            <input type="hidden" name="via" value="Offline">

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
                                <div class="invalid-feedback"></div>
                            </div>
                            @for ($i = 2; $i <= 6; $i++)
                                <div class="mb-3">
                                    <label class="form-label" for="course{{ $i }}">Course{{ $i }}</label>
                                    <select class="js-example-basic-single col-sm-12" id="course{{ $i }}" name="course{{ $i }}">
                                        <option value="" selected>Select Course</option>
                                        @foreach($course as $item)
                                            <option value="{{ $item->code }}">{{ $item->title}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            @endfor

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
                                        <input type="checkbox" id="take_indo_checkbox" name="take_indo" value="yes" onchange="toggleStatus('take_indo_checkbox', 'take_indo_status')">
                                        <span class="switch-state"></span>
                                    </label>
                                    <span id="take_indo_status">No</span>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION FOR AMERTA INTERNSHIP -->
                        <div class="amerta-internship-form" style="display: none;">
                            <h5 class="mt-3">Internship Preferences</h5>
                            <hr>

                            <input type="hidden" name="progCategory" value="Internship">
                            <input type="hidden" name="via" value="Offline">

                            <div class="mb-3">
                                <label class="form-label" for="field_of_study">Preferred field of internship</label>
                                <input type="text" class="form-control" id="field_of_study" name="field_of_study" placeholder="e.g., Tourism, Business, Health, etc.">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Starting Date of Internship -->
                            <div class="mb-3">
                                <label class="form-label" for="start_date_prog">Starting Date of Internship <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date_prog" name="start_date_prog" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- End Date of Internship -->
                            <div class="mb-3">
                                <label class="form-label" for="end_date_prog">End Date of Internship <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date_prog" name="end_date_prog" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="outcome">Expected Learning Outcomes <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="outcome" name="outcome" placeholder="e.g., Logbook, etc.">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="experience">Work or volunteer experience (if any) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="experience" name="experience" placeholder="experience">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- ATTACHMENT AMERTA RESEARCH -->
                        <div class="amerta-research-form" style="display: none;">
                            <h5 class="mt-3">Research Study</h5>
                            <hr>
                            
                            <input type="hidden" name="progCategory" value="Research">
                            <input type="hidden" name="via" value="Offline">
                            
                            <div class="mb-3">
                                <label class="form-label" for="research_proposal">Research Proposal <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="research_proposal" name="research_proposal" accept=".pdf" onchange="validateFileSize(this)" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Starting Date of Research Study -->
                            <div class="mb-3">
                                <label class="form-label" for="start_date_prog">Starting Date of Research Study <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date_prog" name="start_date_prog" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- End Date of Research Study -->
                            <div class="mb-3">
                                <label class="form-label" for="end_date_prog">End Date of Research Study <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date_prog" name="end_date_prog" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="outcome">Expected Research Outcomes <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="outcome" name="outcome" placeholder="e.g., Logbook, etc.">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="supervisor">Preferred Supervisor in Universitas Airlangga (if any) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="supervisor" name="supervisor" placeholder="supervisor in Universitas Airlangga">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    @endif

                   {{-- CHOICE OF SUBJECT | LINGUA REGULAR --}}
                    @if($type === 'lingua')
                        <h5 class="mt-3">LINGUA</h5>
                        <hr>

                        <input type="hidden" name="progCategory" value="Short Program">
                        <input type="hidden" name="via" value="Offline">
                        
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
                                <option value="">Select option</option>
                                <option value="a1">Yes, I joined LINGUA A1 class last semester</option>
                                <option value="a2">Yes, I joined LINGUA A2 class last semester</option>
                                <option value="b1">Yes, I joined LINGUA B1 class last semester</option>
                                <option value="b2">Yes, I joined LINGUA B2 class last semester</option>
                                <option value="no">No, I have not</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    @endif

                    <!-- ATTACHMENT --> 
                    <h5 class="mt-6">Attachment File</h5>
                    <hr>
                        <div class="mb-3">
                            <label class="form-label" for="passport">Passport Identity Page (PDF) <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="passport" name="passport" accept=".pdf" onchange="validateFileSize(this)" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="photo">Most recent passport size photo(red/blue/white background) <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png" onchange="validateFileSize(this)" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="transcript">Official academic transcript in English (issued by the applicant's home institution)</label>
                            <input class="form-control" type="file" id="transcript" name="transcript" accept=".pdf" onchange="validateFileSize(this)" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="letter_recom">One (1) Professional Letter of Recomendation signed by the referee (preferably from a professor, invalid if recommendations are made by family members or friends)</label>
                            <input class="form-control" type="file" id="letter_recom" name="letter_recom" accept=".pdf" onchange="validateFileSize(this)" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="english_certificate">Scanned English Proficiency Certificate <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="english_certificate" name="english_certificate" accept=".pdf" onchange="validateFileSize(this)" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="cv">Curriculum Vitae <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="cv" name="cv" accept=".pdf" onchange="validateFileSize(this)" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="motivation_letter">Motivation Letter</label>
                            <input class="form-control" type="file" id="motivation_letter" name="motivation_letter" onchange="validateFileSize(this)" accept=".pdf">
                            <div class="invalid-feedback"></div>
                        </div>
                    

                    <!-- Declaration Section -->
                    <h5 class="mt-6">Declaration Section</h5>
                    <hr>

                    <div class="mb-3">
                        <label class="form-label" for="program_info">Where did you obtain the information about this program? <span class="text-danger">*</span></label>
                        <select class="form-select" id="program_info" name="program_info" required>
                            <option value="">Select your source of information?</option>
                            <option value="Website">Website</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Twitter">Twitter</option>
                            <option value="Home University">Home University</option>
                            <option value="Ads">Ads</option>
                            <option value="Airlangga Hubs">Airlangga Hubs</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Agreement --}}

                    <div class="form-group form-group-sm">
                        <p style="font-size:9pt; border:1px solid #aaa; padding:10px 20px; cursor:default;">
                            I hereby declare that the information provided in this application is correct and complete. 
                            Any provision of inaccurate or false information or omission of information will render this 
                            application invalid and that, if selected on the basis of such information, I can be required 
                            to withdraw from the  {{ $tipe }}  Program and reimburse any funds provided or expended on my behalf.

                            I authorize the Universities/Institutions to obtain official records, if necessary, from any
                            educational institution attended by me. I am medically fit and free from any medical problem 
                            that may affect my studies.
                        </p>
                        <div class="d-flex align-items-start">
                            <input type="checkbox" id="declaration_agreement" name="declaration_agreement" class="form-check-input me-2" required>
                            <label for="declaration_agreement" class="form-check-label">Yes, I agree</label>
                            <div class="invalid-feedback">You must agree to this declaration.</div>
                        </div>                        
                    </div>

                    <!-- Consent Section -->
                    <div class="form-group form-group-sm">
                        <p style="font-size:9pt; border:1px solid #aaa; padding:10px 20px; cursor:default;">
                            I also give my consent to Universitas Airlangga and its affiliates to use my image and likeness 
                            and/or any interview statements from me in its publications, advertising, or other media activities 
                            (including the internet). This consent includes, but is not limited to: 
                            (a) Permission to interview, film, photograph, tape, or otherwise make a video reproduction of me and/or record my voice; 
                            (b) Permission to use my name; 
                            (c) Permission to use quotes from the interview(s) (or excerpts of such quotes), the film, photograph(s), tape(s), or reproduction(s) of me, and/or recording of my voice, in part or in whole, in its publications, newspapers, magazines, and other print media, on television, radio, and electronic media (including the Internet), in theatrical media, and/or in mailings for educational purposes; and 
                            (d) Permission to edit and/or redact said materials accordingly, prior to publication. This consent is given in perpetuity and does not require prior approval by me.
                        </p>
                        <div class="d-flex align-items-start">
                            <input type="checkbox" id="consent_agreement" name="consent_agreement" class="form-check-input me-2" required>
                            <label for="consent_agreement" class="form-check-label">Yes, I agree</label>
                            <div class="invalid-feedback">You must agree to this consent.</div>
                        </div>                       
                    </div>

                    <!-- Confirmation Section -->
                    <div class="form-group form-group-sm">
                        <p style="font-size:9pt; border:1px solid #aaa; padding:10px 20px; cursor:default;">
                            If admitted, I agree to comply with the rules and regulations of Universitas Airlangga. 
                            By submitting my full name below, I acknowledge the conditions mentioned above.
                        </p>
                        <div class="d-flex align-items-start">
                            <input type="checkbox" id="confirmation_agreement" name="confirmation_agreement" class="form-check-input me-2" required>
                            <label for="confirmation_agreement" class="form-check-label">Yes, I agree</label>
                            <div class="invalid-feedback">You must agree to this confirmation.</div>
                        </div>
                    </div>
                </div>
              
                <!-- Navigation Buttons -->
                <div class="text-end btn-mb">
                    <button class="btn btn-secondary" id="prevBtn" type="button" onclick="nextPrev(-1)">Previous</button>
                    <button class="btn btn-primary" id="nextBtn" type="button" onclick="nextPrev(1)">Next</button>
                </div>
              </form>              
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    let isFileValid = true; // Variabel global untuk melacak status validasi file

    document.addEventListener("DOMContentLoaded", function () {
        const fileInputs = document.querySelectorAll("input[type='file']");
        console.log('percobaan');

        fileInputs.forEach(input => {
            input.addEventListener("change", function () {
                console.log(fileInputs);
                validateFileSize(this); // Jalankan validasi saat file dipilih
            });
        });
    });

    function validateFileSize(input) {
        const file = input.files[0]; // Ambil file pertama dari input
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'File too large!',
                    text: 'The file size exceeds 2 MB. Please upload a smaller file.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = ""; // Kosongkan input file jika tidak valid
                isFileValid = false; // Tandai file tidak valid
            } else {
                isFileValid = true; // Tandai file valid
            }
        }
    }

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    @if(session('file_errors'))
        let errorMessages = "";
        @foreach(session('file_errors') as $error)
            errorMessages += "• {{ $error }}\n";
        @endforeach
        Swal.fire({
            title: 'Validation Failed!',
            text: errorMessages,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            timer: 4000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const selected_program = document.getElementById('selected_program');

    selected_program.addEventListener('change', function () {
        const selectedValue = this.value;

        // Ambil semua elemen dengan kelas yang sesuai
        const regularForms = document.querySelectorAll('.amerta-regular-form');
        const internshipForms = document.querySelectorAll('.amerta-internship-form');
        const researchForms = document.querySelectorAll('.amerta-research-form');

        // Sembunyikan semua form
        hideForms(regularForms);
        hideForms(internshipForms);
        hideForms(researchForms);

        // Tampilkan form yang sesuai dengan pilihan
        if (selectedValue === 'AMERTA') {
            showForms(regularForms);
        } else if (selectedValue === 'Amerta Internship') {
            showForms(internshipForms);
        } else if (selectedValue === 'Amerta Research') {
            showForms(researchForms);
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

<script>
    function toggleStatus(checkboxId, statusId) {
        const checkbox = document.getElementById(checkboxId);
        const statusText = document.getElementById(statusId);
        statusText.textContent = checkbox.checked ? 'Yes' : 'No';
    }
</script>
