@extends('pendaftaran.master')

@section('content') 


<div class="page-body">
<div class="container-fluid">
    <div class="row">

      <div class="col-md-5 d-flex justify-content-center align-items-center" >
        @if($program->logo_base64)
            <img src="{{ $program->logo_base64 }}" alt="Preview" class="img-fluid" style="max-width:100%; height: 300px; object-fit: cover;">
        @else
            <p>Logo tidak tersedia.</p>
        @endif
      </div>

      <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5>{{ $program->name }}</h5>
                <span>{{ $program->description }}</span>
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
              
              <form class="form-wizard" id="regForm" action="{{ route('simpan.registrasi', ['type' => 'amerta']) }}" method="POST">
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
                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Email wajib diisi.</div>
                    </div>
                    
                    <!-- Secondary Email -->
                    <div class="mb-3">
                        <label class="form-label" for="secondary_email">Secondary Email</label>
                        <input class="form-control" id="secondary_email" name="secondary_email">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="mb-3">
                        <label class="form-label" for="type">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="amerta">Amerta</option>
                            <option value="amerta-ua">Amerta Master</option>
                        </select>
                        <div class="invalid-feedback">Type wajib dipilih.</div>
                    </div>
                </div>

              
                <!-- Step 3 -->
                <div id="step-3" class="tab setup-content">
                    <input type="hidden" name="step" value="3">
                    {{-- PERSONAL INFORMATION --}}
                    <h5 class="mt-3">Personal Information</h5>
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label" for="fullName">Full Name *</label>
                        <input type="text" class="form-control" id="fullName" name="full_name" placeholder="Masukkan Nama Lengkap" required>
                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                    </div>

                    <!-- First Name -->
                    <div class="mb-3">
                        <label class="form-label" for="firstName">First Name *</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Masukkan Nama Depan" required>
                        <div class="invalid-feedback">Nama depan wajib diisi.</div>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label class="form-label" for="lastName">Last Name *</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Masukkan Nama Belakang" required>
                        <div class="invalid-feedback">Nama belakang wajib diisi.</div>
                    </div>

                    <!-- Sex -->
                    <div class="mb-3">
                        <label class="form-label" for="sex">Sex *</label>
                        <select class="form-select" id="sex" name="sex" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Prefer Not to Say</option>
                        </select>
                        <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                    </div>

                    <!-- Place of Birth -->
                    <div class="mb-3">
                        <label class="form-label" for="placeOfBirth">Place of Birth *</label>
                        <input type="text" class="form-control" id="placeOfBirth" name="place_of_birth" placeholder="Masukkan Tempat Lahir" required>
                        <div class="invalid-feedback">Tempat lahir wajib diisi.</div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-3">
                        <label class="form-label" for="dateOfBirth">Date of Birth *</label>
                        <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth" required>
                        <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                    </div>

                    <!-- Nationality -->
                    <div class="mb-3">
                        <label class="form-label" for="nationality">Nationality *</label>
                        <select class="form-select" id="nationality" name="nationality" required>
                            @foreach($country as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kewarganegaraan wajib dipilih.</div>
                    </div>

                    <!-- Passport Number -->
                    <div class="mb-3">
                        <label class="form-label" for="passportNumber">Passport Number</label>
                        <input type="text" class="form-control" id="passportNumber" name="passport_number" placeholder="Masukkan Nomor Paspor">
                    </div>

                    <!-- Passport Date of Issue -->
                    <div class="mb-3">
                        <label class="form-label" for="passportDateIssue">Passport Date of Issue</label>
                        <input type="date" class="form-control" id="passportDateIssue" name="passport_date_issue">
                    </div>

                    <!-- Passport Date of Expiration -->
                    <div class="mb-3">
                        <label class="form-label" for="passportDateExpiration">Passport Date of Expiration</label>
                        <input type="date" class="form-control" id="passportDateExpiration" name="passport_date_expiration">
                    </div>

                    <!-- Issuing Authority -->
                    <div class="mb-3">
                        <label class="form-label" for="issuingAuthority">Issuing Authority</label>
                        <input type="text" class="form-control" id="issuingAuthority" name="issuing_authority" placeholder="Masukkan Otoritas Penerbit Paspor">
                    </div>

                    <!-- Telephone Number -->
                    <div class="mb-3">
                        <label class="form-label" for="telephoneNumber">Telephone Number *</label>
                        <input type="tel" class="form-control" id="telephoneNumber" name="telephone_number" placeholder="Masukkan Nomor Telepon" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                    </div>

                    <!-- Mobile/Cellular Number -->
                    <div class="mb-3">
                        <label class="form-label" for="mobileNumber">Mobile/Cellular Number *</label>
                        <input type="tel" class="form-control" id="mobileNumber" name="mobile_number" placeholder="Masukkan Nomor Ponsel" required>
                        <div class="invalid-feedback">Nomor ponsel wajib diisi.</div>
                    </div>

                    <!-- Home Address -->
                    <div class="mb-3">
                        <label class="form-label" for="homeAddress">Home Address *</label>
                        <textarea class="form-control" id="homeAddress" name="home_address" placeholder="Masukkan Alamat Rumah" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat rumah wajib diisi.</div>
                    </div>

                    <!-- Mailing Address -->
                    <div class="mb-3">
                        <label class="form-label" for="mailingAddress">Mailing Address *</label>
                        <textarea class="form-control" id="mailingAddress" name="mailing_address" placeholder="Masukkan Alamat Surat" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                    </div>

                    <!-- Indonesian Embassy Address -->
                    <div class="mb-3">
                        <label class="form-label" for="embassyAddress">Indonesian Embassy Address</label>
                        <textarea class="form-control" id="embassyAddress" name="embassy_address" placeholder="Masukkan Alamat Kedutaan Indonesia" rows="2"></textarea>
                    </div>

                    <!-- Particular Next of Kin -->
                    <h5 class="mt-3">Particular Next of Kin</h5>
                    <div class="mb-3">
                        <label class="form-label" for="kinName">Name *</label>
                        <input type="text" class="form-control" id="kinName" name="kin_name" placeholder="Masukkan Nama Kerabat" required>
                        <div class="invalid-feedback">Nama wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="kinRelationship">Relationship *</label>
                        <input type="text" class="form-control" id="kinRelationship" name="kin_relationship" placeholder="Supervisor, Father, etc." required>
                        <div class="invalid-feedback">Hubungan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="kinAddress">Address *</label>
                        <textarea class="form-control" id="kinAddress" name="kin_address" placeholder="Masukkan Alamat" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="kinEmail">Email *</label>
                        <input type="email" class="form-control" id="kinEmail" name="kin_email" placeholder="Email Kerabat" required>
                        <div class="invalid-feedback">Email wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="kinTelephone">Telephone Number *</label>
                        <input type="tel" class="form-control" id="kinTelephone" name="kin_telephone" placeholder="Nomor Telepon Kerabat" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="kinMobile">Mobile/Cellular Number</label>
                        <input type="tel" class="form-control" id="kinMobile" name="kin_mobile" placeholder="Nomor Ponsel Kerabat">
                    </div>
        
                    <!-- Educational Qualification -->
                    <h5 class="mt-3">Educational Qualification</h5>
                    <div class="mb-2">
                        <label class="form-label" for="univasal">Home University</label>
                        <select class="form-select col-sm-12" id="univasal" name="univasal">
                        @foreach($univ as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="degree">Degree Being Taken at University (Major) *</label>
                        <input type="text" class="form-control" id="degree" name="degree" placeholder="Biotechnology, etc." required>
                        <div class="invalid-feedback">Program studi wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="gpa">GPA *</label>
                        <input type="text" class="form-control" id="gpa" name="gpa" placeholder="Masukkan GPA (contoh: 3.3)" required>
                        <div class="invalid-feedback">GPA wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="yearEntry">Year of Entry *</label>
                        <input type="date" class="form-control" id="yearEntry" name="year_entry" required>
                        <div class="invalid-feedback">Tahun masuk wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="nativeLanguage">Native Language *</label>
                        <input type="text" class="form-control" id="nativeLanguage" name="native_language" placeholder="Kazakh, etc." required>
                        <div class="invalid-feedback">Bahasa asli wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="englishScore">English Proficiency Score *</label>
                        <input type="text" class="form-control" id="englishScore" name="english_score" placeholder="Masukkan nilai Bahasa Inggris (contoh: 3.67/4)" required>
                        <div class="invalid-feedback">Nilai Bahasa Inggris wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="fieldStudy">Field of Study</label>
                        <input type="text" class="form-control" id="fieldStudy" name="field_of_study" placeholder="AMERTA Internship Only">
                    </div>
        
                    <!-- Character Reference -->
                    <h5 class="mt-3">Character Reference</h5>
                    <div class="mb-3">
                        <label class="form-label" for="refereeName">Name of Referee *</label>
                        <input type="text" class="form-control" id="refereeName" name="referee_name" placeholder="Masukkan Nama Rujukan" required>
                        <div class="invalid-feedback">Nama rujukan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="refereeOrganization">Organization and Position *</label>
                        <input type="text" class="form-control" id="refereeOrganization" name="referee_organization" placeholder="Father, etc." required>
                        <div class="invalid-feedback">Organisasi dan jabatan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="refereeRelationship">Relationship to Applicant *</label>
                        <input type="text" class="form-control" id="refereeRelationship" name="referee_relationship" placeholder="Masukkan Hubungan" required>
                        <div class="invalid-feedback">Hubungan dengan rujukan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="refereeEmail">Email *</label>
                        <input type="email" class="form-control" id="refereeEmail" name="referee_email" placeholder="Masukkan Email Rujukan" required>
                        <div class="invalid-feedback">Email rujukan wajib diisi.</div>
                    </div>
        
                    <!-- Contact Person at Home University -->
                    <h5 class="mt-3">Details of The Contact Person at Home University</h5>
                    <div class="mb-3">
                        <label class="form-label" for="contactName">Contact Person *</label>
                        <input type="text" class="form-control" id="contactName" name="contact_name" placeholder="Daryn Korpebayev" required>
                        <div class="invalid-feedback">Kontak wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="contactPosition">Position *</label>
                        <input type="text" class="form-control" id="contactPosition" name="contact_position" placeholder="Academic Mobility Department Manager" required>
                        <div class="invalid-feedback">Jabatan wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="contactEmail">Email *</label>
                        <input type="email" class="form-control" id="contactEmail" name="contact_email" placeholder="Masukkan Email Kontak" required>
                        <div class="invalid-feedback">Email wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="contactTelephone">Telephone Number *</label>
                        <input type="tel" class="form-control" id="contactTelephone" name="contact_telephone" placeholder="Masukkan Nomor Telepon" required>
                        <div class="invalid-feedback">Nomor telepon wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="contactMailingAddress">Mailing Address *</label>
                        <textarea class="form-control" id="contactMailingAddress" name="contact_mailing_address" placeholder="Masukkan Alamat Surat" rows="2" required></textarea>
                        <div class="invalid-feedback">Alamat surat wajib diisi.</div>
                    </div>
        
                    <!-- Choice of Subjects -->
                    <h5 class="mt-3">Choice of Subjects</h5>
                    @for ($i = 1; $i <= 6; $i++)
                        <div class="mb-2">
                            {{-- <label class="form-label" for="matkul">Mata Kuliah {{ i }}</label> --}}
                            {{-- <select class="js-example-basic-single col-sm-12" id="univTujuanPeserta" name="univTujuanPeserta">
                            @foreach($matkul as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                            </select> --}}
                        </div>
                    @endfor
                    <div class="media mb-3 align-items-center">
                        <label class="col-form-label me-3">Have you ever taken bahasa indonesia course?</label>
                        <div class="media-body text-end icon-state d-flex align-items-center">
                            <input type="hidden" name="is_active" value="No">
                            <label class="switch me-2">
                                <input type="checkbox" id="is_active_checkbox" name="is_active" value="Yes" 
                                    onchange="toggleStatusText()" {{ (isset($is_active) && $is_active === 'Yes') ? 'checked' : '' }}>
                                <span class="switch-state"></span>
                            </label>
                            <!-- Status Text -->
                            <span id="status_text">{{ isset($is_active) && $is_active === 'Yes' ? 'Yes' : 'No' }}</span>
                        
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Taking Bahasa Indonesia Course as Complementary Course?</label>
                        <div class="media-body text-end icon-state d-flex align-items-center">
                            <input type="hidden" name="is_active" value="No">
                            <label class="switch me-2">
                                <input type="checkbox" id="is_active_checkbox" name="is_active" value="Yes" 
                                    onchange="toggleStatusText()" {{ (isset($is_active) && $is_active === 'Yes') ? 'checked' : '' }}>
                                <span class="switch-state"></span>
                            </label>
                            <!-- Status Text -->
                            <span id="status_text">{{ isset($is_active) && $is_active === 'Yes' ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                    

                    <!-- Declaration Section -->
                    <div class="form-group form-group-sm">
                        <p style="font-size:9pt; border:1px solid #aaa; padding:10px 20px; cursor:default;">
                            I hereby declare that the information provided in this application is correct and complete. 
                            Any provision of inaccurate or false information or omission of information will render this 
                            application invalid and that, if selected on the basis of such information, i can be required 
                            to withdraw from the LINGUA Program and reimburse and funds provided or expended on my behalf.
                            
                            I authorize the Universities/Institutions to obtain official records, if necessary, from any
                            educational institution attended by me. I am medically fit and free from any medical problem 
                            that may affect my studies
                        </p>
                        <div class="d-flex align-items-start">
                            <input type="checkbox" id="concern1_Y" name="concern1[Y]" class="form-check-input me-2">
                            <label for="concern1_Y" class="form-check-label">Yes, I agree</label>
                        </div>                        
                    </div>

                    <!-- Agreement Section -->
                    <div class="form-group form-group-sm">
                        <p style="font-size:9pt; border:1px solid #aaa; padding:10px 20px; cursor:default;">
                            I also give my consent to Universitas Airlangga and its affiliates to use my image and likeness 
                            and/or any interview statements from me in its publications, advertising or other media activities 
                            (including the internet). This consent includes, but not limited to : (a) Permission to interview, 
                            film, photograph, tape, or otherwise make a video reproduction of me and/or record my voice; (b) 
                            Permission to use my name; (c) Permission to use quotes from the interview(s) (or excerpts of such quotes), 
                            the film, photograph(s), tape(s) or reproduction(s) of me, and/or recording of my voice, in part or in 
                            whole, in ints publications, in newspapers, magazines and other print media, on television, radio and 
                            electronic media (including the Internet), in theatrical media and/or in mailings for educational purposes; 
                            and (d) Permission to edit and/or redact said materials accordingly, prior to publications. This consent 
                            is given in perpetuity, and does not require prior approval by me.
                        </p>
                        <div class="d-flex align-items-start">
                            <input type="checkbox" id="concern1_Y" name="concern1[Y]" class="form-check-input me-2">
                            <label for="concern1_Y" class="form-check-label">Yes, I agree</label>
                        </div>                       
                    </div>

                    <!-- Confirmation Section -->
                    <div class="form-group form-group-sm">
                        <p style="font-size:9pt; border:1px solid #aaa; padding:10px 20px; cursor:default;">
                            If admitted, I agree to comply with the rules and regulations of Universitas Airlangga. 
                            By submitting my full name below, I acknowledge the conditions mentioned above.
                        </p>
                        <div class="d-flex align-items-start">
                            <input type="checkbox" id="concern1_Y" name="concern1[Y]" class="form-check-input me-2">
                            <label for="concern1_Y" class="form-check-label">Yes, I agree</label>
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
@endsection