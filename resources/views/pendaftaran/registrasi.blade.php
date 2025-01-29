@extends('pendaftaran.master')

@section('content') 


<div class="page-body">
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
              
            <form class="form-wizard" id="regForm" action="{{ route('simpan.registrasi', ['type' => 'amerta']) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Step 1 -->
            <div id="step-1" class="tab setup-content">
                <input type="hidden" name="step" value="1">

                <div class="title">INSTRUCTION</div>
                    <ol>
                        <li>This Application Form must be fully completed in the required format.</li>
                        <li>Please indicate "NA" if an item is not applicable.</li>
                        <li>The Application Form consists of three sections:
                            <ul>
                            <li>Personal Information</li>
                            <li>Educational Qualifications</li>
                            <li>Choice of Subjects</li>
                            </ul>
                        </li>
                    </ol>
                    <br>
                    
                    <p>Please upload the required supporting documents along with this application form:</p>
                    <ol>
                        <li>Student Card</li>
                        <li>Most recent passport-size photo (red/blue/white background)</li>
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
                            <i class="fa fa-envelope-o" style="margin-right:5px; color: #222;"></i>inbound@global.unair.co.id
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
                            <label class="form-label" for="selected_program">Pilih Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" id="selected_program" name="selected_program" required>
                                <option value="">Pilih Program</option>
                                <option value="Amerta Regular">Amerta Regular</option>
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
                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Masukkan Nama Lengkap" required>
                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                    </div>

                    <!-- First Name -->
                    <div class="mb-3">
                        <label class="form-label" for="firstname">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Masukkan Nama Depan" required>
                        <div class="invalid-feedback">Nama depan wajib diisi.</div>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label class="form-label" for="lastname">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Masukkan Nama Belakang" required>
                        <div class="invalid-feedback">Nama belakang wajib diisi.</div>
                    </div>

                    <!-- Sex -->
                    <div class="mb-3">
                        <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                        <select class="form-select" id="sex" name="sex" required>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                            <option value="O">Prefer Not to Say</option>
                        </select>
                        <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                    </div>

                    <!-- Place of Birth -->
                    <div class="mb-3">
                        <label class="form-label" for="pob">Place of Birth <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pob" name="pob" placeholder="Masukkan Tempat Lahir" required>
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
                        <input type="text" class="form-control" id="passport_number" name="passport_number" placeholder="Masukkan Nomor Paspor" required>
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
                        <input type="text" class="form-control" id="issuing_authority" name="issuing_authority" placeholder="Masukkan Otoritas Penerbit Paspor" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Telephone Number -->
                    <div class="mb-3">
                        <label class="form-label" for="telephone">Telephone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Masukkan Nomor Telepon" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                    </div>

                    <!-- Mobile/Cellular Number -->
                    <div class="mb-3">
                        <label class="form-label" for="phone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Masukkan Nomor Ponsel" required>
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
                        <input type="text" class="form-control" id="kin_name" name="kin_name" placeholder="Masukkan Nama Kerabat" required>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="kin_relation">Relationship <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kin_relation" name="kin_relation" placeholder="Supervisor, Father, etc." required>
                        <div class="invalid-feedback">Hubungan wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="kin_address">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="kin_address" name="kin_address" placeholder="Masukkan Alamat" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="kin_email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="kin_email" name="kin_email" placeholder="Email Kerabat" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="kin_telephone">Telephone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="kin_telephone" name="kin_telephone" placeholder="Nomor Telepon Kerabat" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="kin_homephone">Mobile/Cellular Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="kin_homephone" name="kin_homephone" placeholder="Nomor Ponsel Kerabat">
                    </div>
        
                    <!-- Educational Qualification -->
                    <h5 class="mt-3">Educational Qualification</h5>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label" for="university">Home University <span class="text-danger">*</span></label>
                        <select class="form-select js-example-basic-single" id="university" name="university">
                        @foreach($univ as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="major">Degree Being Taken at University (Major) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="major" name="major" placeholder="Biotechnology, etc." required>
                        <div class="invalid-feedback">Program studi wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="gpa">GPA <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="gpa" name="gpa" placeholder="Masukkan GPA (contoh: 3.3)" required>
                        <div class="invalid-feedback">GPA wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="year_entry">Year of Entry <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="year_entry" name="year_entry" required>
                        <div class="invalid-feedback">Tahun masuk wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="native">Native Language <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="native" name="native" placeholder="Kazakh, etc." required>
                        <div class="invalid-feedback">Bahasa asli wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="english_score">English Proficiency Score *</label>
                        <input type="text" class="form-control" id="english_score" name="english_score" placeholder="Masukkan nilai Bahasa Inggris (contoh: 3.67/4)">
                        <div class="invalid-feedback">Nilai Bahasa Inggris wajib diisi.</div>
                    </div>

                    
                    <div class="amerta-internship-form" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label" for="field_of_study">Field of Study</label>
                            <input type="text" class="form-control" id="field_of_study" name="field_of_study" placeholder="AMERTA Internship Only">
                        </div>
                    </div>
        
                    <!-- Character Reference -->
                    <h5 class="mt-3">Character Reference</h5>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label" for="referee_name">Name of Referee <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="referee_name" name="referee_name" placeholder="Masukkan Nama Rujukan" required>
                        <div class="invalid-feedback">Nama rujukan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="referee_organization">Organization and Position <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="referee_organization" name="referee_organization" placeholder="Father, etc." required>
                        <div class="invalid-feedback">Organisasi dan jabatan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="referee_relation">Relationship to Applicant <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="referee_relation" name="referee_relation" placeholder="Masukkan Hubungan" required>
                        <div class="invalid-feedback">Hubungan dengan rujukan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="referee_email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="referee_email" name="referee_email" placeholder="Masukkan Email Rujukan" required>
                        <div class="invalid-feedback">Email rujukan wajib diisi.</div>
                    </div>
        
                    <!-- Contact Person at Home University -->
                    <h5 class="mt-3">Details of The Contact Person at Home University</h5>
                    <div class="mb-3">
                        <label class="form-label" for="pic_name">Contact Person <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pic_name" name="pic_name" placeholder="Daryn Korpebayev" required>
                        <div class="invalid-feedback">Kontak wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pic_position">Position <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pic_position" name="pic_position" placeholder="Academic Mobility Department Manager" required>
                        <div class="invalid-feedback">Jabatan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pic_email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="pic_email" name="pic_email" placeholder="Masukkan Email Kontak" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pic_telephone">Telephone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="pic_telephone" name="pic_telephone" placeholder="Masukkan Nomor Telepon" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pic_address">Mailing Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="pic_address" name="pic_address" placeholder="Masukkan Alamat Surat" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                    </div>
        
                    <!-- Choice of Subjects -->
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
                            <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
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

                    <!-- Declaration Section -->
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
                    
                    <div class="amerta-research-form" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label" for="research_proposal">Research Proposal</label>
                            <input class="form-control" type="file" id="research_proposal" name="research_proposal" accept=".pdf" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Declaration Section -->
                    <h5 class="mt-6">Declaration Section</h5>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label" for="letter_recom">Recommendation Letter From Home University (PDF) <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="letter_recom" name="letter_recom" accept=".pdf" required>
                        <div class="invalid-feedback"></div>
                      </div>

                    <div class="mb-3">
                        <label class="form-label" for="program_info">Where did you obtain the information about this program? <span class="text-danger">*</span></label>
                        <select class="form-select" id="program_info" name="program_info" required>
                            <option value="">Pilih Program</option>
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

                    <div class="form-group form-group-sm">
                        <p style="font-size:9pt; border:1px solid #aaa; padding:10px 20px; cursor:default;">
                            I hereby declare that the information provided in this application is correct and complete. 
                            Any provision of inaccurate or false information or omission of information will render this 
                            application invalid and that, if selected on the basis of such information, I can be required 
                            to withdraw from the LINGUA Program and reimburse any funds provided or expended on my behalf.

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
                <div>
                  <div class="text-end btn-mb">
                    <button class="btn btn-secondary" id="prevBtn" type="button" onclick="nextPrev(-1)">Previous</button>
                    <button class="btn btn-primary" id="nextBtn" type="button" onclick="nextPrev(1)">Next</button>
                  </div>
                </div>
              </form>              
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


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
        if (selectedValue === 'Amerta Regular') {
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
            if (isDisabled) {
                input.removeAttribute('required');
            } else {
                input.setAttribute('required', true);
            }
        });
    }

    function toggleStatus(checkboxId, statusId) {
        console.log(`toggleStatusText dipanggil dengan checkboxId: ${checkboxId}, statusId: ${statusId}`);
        
        const checkbox = document.getElementById(checkboxId);
        const statusText = document.getElementById(statusId);

        if (!checkbox) {
            console.error(`Checkbox dengan id "${checkboxId}" tidak ditemukan.`);
            return;
        }
        if (!statusText) {
            console.error(`Status dengan id "${statusId}" tidak ditemukan.`);
            return;
        }

        console.log(`Checkbox checked: ${checkbox.checked}`);
        statusText.textContent = checkbox.checked ? 'Yes' : 'No';
    }

});


</script>

@endsection
