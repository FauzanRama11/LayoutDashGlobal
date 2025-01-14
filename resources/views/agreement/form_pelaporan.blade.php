@extends('layouts.master') 

@section('content') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<div class="card">
  <div class="card-header pb-0">
    <h5>Form Pelaporan</h5><span>This is Form Pelaporan</span>
  </div>

    <div class="card-body">
    @if(isset($data))
    @hasrole("gpc")
            <div class="d-flex justify-content-start" style="margin-bottom: 15px;">
              @if(isset($data) && isset($data->approval_pelaporan) && $data->approval_pelaporan != 1)
                    <form style="margin: 5px;" action="{{ route('pelaporan.approve', ['id' => $data-> id]) }}" method="POST">
                        @csrf
                        @method('PUT') 
                        <button type="submit" class="btn btn-primary mr-2">Approved</button>
                    </form>

                      <button style="margin: 5px;" type="submit" id = "rejectButton" class="btn btn-danger mr-2">Rejected</button>
                      <button style="margin: 5px;" type="submit" id = "reviseButton" class="btn btn-warning">Revise</button>
              @endif
          </div>

          <div id="reNotes">
            <form action="{{ route('pelaporan.revise', ['id' => $data-> id]) }}" method = "POST">
              @csrf
              @method('PUT') 
                <div class="mb-3">
                  <label class="form-label" for="notes">Notes</label>
                  <textarea class="form-control" id="notes" name="notes" placeholder="Notes" required>{{ old('Notes', isset($data) ? $data->approval_note : '') }}</textarea>
                  <div class="invalid-feedback">Notes wajib diisi.</div>
                </div>
              <button type="submit" id = "reviseButton" class="btn btn-warning">Click to Revise</button>
            </form>
          </div>
          <div id="reNotes2">
            <form action="{{ route('pelaporan.reject', ['id' => $data-> id]) }}" method = "POST">
              @csrf
              @method('PUT') 
                <div class="mb-3">
                  <label class="form-label" for="notes">Notes</label>
                  <textarea class="form-control" id="notes" name="notes" placeholder="Notes" required>{{ old('Notes', isset($data) ? $data->approval_note : '') }}</textarea>
                  <div class="invalid-feedback">Notes wajib diisi.</div>
                </div>
              <button type="submit" id = "reviseButton" class="btn btn-danger">Click to Reject</button>
            </form>
          </div>
      @endhasrole
    @endif

    @hasrole('wadek3')
      @if(isset($data) && isset($data->approval_note))
        <label class="form-label" for="notesRead">Revision or Rejection Notes</label>
        <textarea class="form-control" id="notesRead" name="notesRead" placeholder="notesRead" readonly>{{ old('Notes', isset($data) ? $data->approval_note : '') }}</textarea>
        <div class="invalid-feedback">.</div>
        @endif
    @endhasrole
        
    <form class="was-validated" action =  "{{ isset($data) ? route('pelaporan.update', $data->id) :  url('/store-pelaporan') }} " method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($data))
            @method('PUT') <!-- Untuk update, menambahkan method PUT -->
        @endif

      <div class="mb-3">
        <label class="form-label" for="jenisP">Type of Agreement</label>
        <select class="form-select" id="jenisP" name="jenisP" required>
            <option value="General" {{ old('jenisP', isset($data->tipe_moa) ? $data->tipe_moa : '') == 'General' ? 'selected' : '' }}>General</option>
            <option value="Riset" {{ old('jenisP', isset($data->tipe_moa) ? $data->tipe_moa : '') == 'Riset' ? 'selected' : '' }}>Riset</option>


        </select>
      </div>

      <div class="mb-3">
        <label class="form-label" for="triDharma">Kategori Tri Dharma</label>
        <select class="form-select" id="triDharma" name="triDharma" required>
            <option value="Pendidikan" {{ old('triDharma', isset($data->kategori_tridharma) ? $data->kategori_tridharma : '') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
            <option value="Riset" {{ old('triDharma', isset($data->kategori_tridharma) ? $data->kategori_tridharma : '') == 'Riset' ? 'selected' : '' }}>Riset</option>
            <option value="Pengabdian Masyarakat" {{ old('triDharma', isset($data->kategori_tridharma) ? $data->kategori_tridharma : '') == 'Pengabdian Masyarakat' ? 'selected' : '' }}>Pengabdian Masyarakat</option>
        </select>
      </div>

      <div class="mb-2">
        <label class="form-label" for="unitP">Faculty/Unit (UNAIR) Pengaju</label>
        <select class="js-example-basic-single col-sm-12" id="unitP" name="unitP" required>
          @foreach($unit as $item)
            <option value="{{ $item->id }}" {{ old('unitP', isset($data->id_fakultas) ? $data->id_fakultas : '') == $item->id ? 'selected' : '' }}>{{ $item->nama_ind }}</option>
          @endforeach
        </select>
      </div>

    <div class="mb-2">
        <label class="form-label" for="countryP">Country</label>
        <select class="js-example-basic-single col-sm-12" id="countryP" name="countryP" required>
            @foreach($country as $item)
                <option value="{{ $item->id }}" {{ old('countryP', $data->id_country ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-2">
        <label class="col-form-label" for="partnerP">University Partner</label>
        <select class="js-example-placeholder-multiple col-sm-12" id="partnerP" name="partnerP[]" multiple="multiple">
            @foreach ($univ as $item)
                <option value="{{ $item->id }}" {{ in_array($item->id, old('partnerP', [])) ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="docP">Type of Document</label>
        <select class="form-select" id="docP" name="docP" required>
            <option value="MoU" {{ old('docP', $data->jenis_naskah ?? '') == 'MoU' ? 'selected' : '' }}>MoU</option>
            <option value="MoA" {{ old('docP', $data->jenis_naskah ?? '') == 'MoA' ? 'selected' : '' }}>MoA</option>
            <option value="IA" {{ old('docP', $data->jenis_naskah ?? '') == 'IA' ? 'selected' : '' }}>IA</option>
        </select>
    </div>


      <div class="mb-3">
            <label class="form-label" for="tittleP">Tittle of Agreement</label>
            <textarea class="form-control" id="tittleP" name="tittleP" placeholder="Judul Program" required>{{ old('tittleP', isset($data) ? $data->title : '') }}</textarea>
            <div class="invalid-feedback">Tittle of agreement wajib diisi.</div>
        </div>

        <div class="mb-2">
            <label class="col-form-label" for="scopeP">Scope of Agreement</label>
            <select class="js-example-placeholder-multiple col-sm-12"  id="scopeP" name="scopeP[]" multiple="multiple">
        @foreach ($scope as $item)
        <option value="{{ $item->id }}" {{ in_array($item->id, old('scopeP', $data->scope_ids ?? [])) ? 'selected' : '' }}>{{ $item->name }}</option>
        @endforeach
        </select>
            </div>

        <div class="mb-3">
            <label class="form-label" for="linkDownload">Link Download Agreement</label>
            <textarea class="form-control" id="linkDownload" name="linkDownload" placeholder="Link Download" required>{{ old('linkDownload', isset($data) ? $data->link_download_naskah : '') }}</textarea>
            <div class="invalid-feedback">Link download agreement wajib diisi.</div>
        </div>

        <div class="mb-3">
        <label class="form-label" for="startDate">Started Date</label>
        <input type="date" class="form-control" id="startDate" name="startDate" value="{{ old('startDate', isset($data)? $data->mou_start_date : '')}}" required>
        <div class="invalid-feedback">Tanggal mulai wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="endDate">Ended Date</label>
        <input type="date" class="form-control" id="endDate" name="endDate" value="{{ old('endDate', isset($data) ? $data->mou_end_date : '')}}" required>
        <div class="invalid-feedback">Tanggal berakhir wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="deptP">Department</label>
        <select  class="js-example-basic-single col-sm-12"  id="deptP" name="deptP" required>
            @foreach ($department as $item)
                <option value="{{$item -> id}}">{{$item->nama_ind}}</option>
            @endforeach
        </select>
      </div>

      <div class="mb-2">
            <label class="col-form-label" for="FacP">Faculty</label>
            <select class="js-example-placeholder-multiple col-sm-12" id="FacP" name="FacP[]" multiple="multiple">
        @foreach ($unit as $item)
            <option value="{{ $item->id }}">{{ $item->nama_ind }}</option>
        @endforeach
        </select>
        </div>

        <div class="mb-2">
            <label class="col-form-label" for="stuProgP">Study Program</label>
            <select class="js-example-placeholder-multiple col-sm-12" id="stuProgP" name="stuProgP[]"  multiple="multiple">
            @foreach ($prodi as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
            </select>
        </div>

        <div class="mb-3">
        <label class="form-label" for="partDept">Partner's Department</label>
        <input class="form-control" id="partDept" name="partDept" placeholder="Partner's Department" value = "{{ old('partDept', isset($data) ? $data->department_partner : '') }}" required>
        <div class="invalid-feedback">Partner's department wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="partnerFac">Partner's Faculty</label>
        <input class="form-control" id="partnerFac" name="partnerFac" placeholder="Partner's Faculty" value = "{{ old('partnerFac', isset($data) ? $data->faculty_partner : '') }}" required>
        <div class="invalid-feedback">Partner's faculty wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="partnerStuProg">Partner's Study Program</label>
        <input class="form-control" id="partnerStuProg" name="partnerStuProg" placeholder="Partner's Study Program" value = "{{ old('partnerStuProg', isset($data) ? $data->program_study_partner : '') }}" required>
        <div class="invalid-feedback">Partner's study program wajib diisi.</div>
      </div>

      <!-- Type of Partner -->
<div class="mb-3">
    <label class="form-label" for="typeP">Type of Partner</label>
    <select class="form-select" id="typeP" name="typeP" required>
        <option value="University/Higher Education Institution (HEI)" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'University/Higher Education Institution (HEI)' ? 'selected' : '' }}>University/Higher Education Institution (HEI)</option>
        <option value="TOP 200 QS by Subject University/Higher Education Institution" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'TOP 200 QS by Subject University/Higher Education Institution' ? 'selected' : '' }}>TOP 200 QS by Subject University/Higher Education Institution</option>
        <option value="Top National Company" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Top National Company' ? 'selected' : '' }}>Top National Company</option>
        <option value="Multinational Company" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Multinational Company' ? 'selected' : '' }}>Multinational Company</option>
        <option value="Global Tech Company" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Global Tech Company' ? 'selected' : '' }}>Global Tech Company</option>
        <option value="World Class Tech Startup" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'World Class Tech Startup' ? 'selected' : '' }}>World Class Tech Startup</option>
        <option value="Non-Profit Organisation" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Non-Profit Organisation' ? 'selected' : '' }}>Non-Profit Organisation</option>
        <option value="Government/BUMN/BUMD" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Government/BUMN/BUMD' ? 'selected' : '' }}>Government/BUMN/BUMD</option>
        <option value="Hospital" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Hospital' ? 'selected' : '' }}>Hospital</option>
        <option value="Enterprise" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
        <option value="Educational Institution" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Educational Institution' ? 'selected' : '' }}>Educational Institution</option>
        <option value="Organisation" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Organisation' ? 'selected' : '' }}>Organisation</option>
        <option value="Government, private, national, or even international research centre" {{ old('typeP', isset($data->type_institution_partner) ? $data->type_institution_partner : '') == 'Government, private, national, or even international research centre' ? 'selected' : '' }}>Government, private, national, or even international research centre</option>
    </select>
</div>

<!-- Conditional Fields for Riset -->

    <div class="riset">
        <!-- Source of Funding -->
        <div class="mb-3">
            <label class="form-label" for="sourceFund">Source of Funding</label>
            <select class="form-select" id="sourceFund" name="sourceFund" required>
                <option value="Universitas Airlangga" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Universitas Airlangga' ? 'selected' : '' }}>Universitas Airlangga</option>
                <option value="Fakultas" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                <option value="Mitra Institusi" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Mitra Institusi' ? 'selected' : '' }}>Mitra Institusi</option>
                <option value="Lembaga Donor Dalam Negeri" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Lembaga Donor Dalam Negeri' ? 'selected' : '' }}>Lembaga Donor Dalam Negeri</option>
                <option value="Lembaga Donor Luar Negeri" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Lembaga Donor Luar Negeri' ? 'selected' : '' }}>Lembaga Donor Luar Negeri</option>
                <option value="LPPM" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'LPPM' ? 'selected' : '' }}>LPPM</option>
                <option value="Mandiri" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                <option value="Warek RICD" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Warek RICD' ? 'selected' : '' }}>Warek RICD</option>
                <option value="Matching Fund" {{ old('sourceFund', isset($data->source_funding) ? $data->source_funding : '') == 'Matching Fund' ? 'selected' : '' }}>Matching Fund</option>
            </select>
        </div>

        <!-- Sum of Funding -->
        <div class="col-md-4 mb-3">
            <label class="form-label" for="sumFund">Sum of Funding</label>
            <div class="input-group"><span class="input-group-text" id="inputGroupPrepend">Rp</span>
                <input class="form-control" id="sumFund" type="number" name="sumFund" placeholder="Sum of Fund (Rp)" aria-describedby="inputGroupPrepend" value="{{ old('sumFund', isset($data->sum_funding) ? $data->sum_funding : '') }}" required>
                <div class="invalid-feedback">Sum of fund wajib diisi</div>
            </div>
        </div>
    </div>


      <div class="card-header pb-0">
          <h5>Penandatanganan</h5><span>Informasi Penandatanganan</span>
        </div>
      <div class="card-body">
        
      <div class="mb-3">
        <label class="form-label" for="nosUnair">Name of Signatory (UNAIR)</label>
        <input class="form-control" id="nosUnair" name="nosUnair" placeholder="Name of Signatory (UNAIR)"  value = "{{ old('nosUnair', isset($data) ? $data->signatories_unair_name : '') }}" required>
        <div class="invalid-feedback">Name of signatory (UNAIR) wajib diisi.</div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="nopUnair">Name of Position (UNAIR)</label>
        <input class="form-control" id="nopUnair" name="nopUnair" placeholder="Name of Position (UNAIR)"  value = "{{ old('nopUnair', isset($data) ? $data->signatories_unair_pos : '') }}" required>
        <div class="invalid-feedback">Name of position (UNAIR) wajib diisi.</div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="nosPart">Name of Signatory (Partner)</label>
        <input class="form-control" id="nosPart" name="nosPart" placeholder="Name of Signatory (Partner)"  value = "{{ old('nosPart', isset($data) ? $data->signatories_partner_name : '') }}" required>
        <div class="invalid-feedback">Name of signatory (partner) wajib diisi.</div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="nopPart">Name of Position (Partner)</label>
        <input class="form-control" id="nopPart" name="nopPart" placeholder="Name of Position (Partner)"  value = "{{ old('nopPart', isset($data) ? $data->signatories_partner_pos : '') }}" required>
        <div class="invalid-feedback">Name of position (partner) wajib diisi.</div>
      </div>  
    </div>

    <div class="card-header pb-0">
          <h5>Person in Charge (PIC)</h5><span>Informasi PIC</span>
        </div>
      <div class="card-body">
        
      <div class="mb-3">
        <label class="form-label" for="namePic">Name (Partner PIC)</label>
        <input class="form-control" id="namePic" name="namePic" placeholder="Name (Partner PIC)" value = "{{ old('namePic', isset($data) ? $data->pic_mitra_nama : '') }}"required>
        <div class="invalid-feedback">Name (partner PIC) wajib diisi.</div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="postPic">Name of Position (Partner PIC)</label>
        <input class="form-control" id="postPic" name="postPic" placeholder="Name of Position (Partner PIC)" value = "{{ old('postPic', isset($data) ? $data->pic_mitra_jabatan : '') }}" required>
        <div class="invalid-feedback">Name of position (partner PIC) wajib diisi.</div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="emailPic">Email (Partner PIC)</label>
        <input type="email" class="form-control" id="emailPic" name="emailPic" placeholder="Email (Partner PIC)" value = "{{ old('emailPic', isset($data) ? $data->pic_mitra_email : '') }}" required>
        <div class="invalid-feedback">Email (Partner PIC) wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="telpPic">No. Telepon (Partner PIC)</label>
        <input class="form-control" id="telpPic" name="telpPic" type="number" placeholder="Nomor Telepon (Partner PIC)" value = "{{ old('telpPic', isset($data) ? $data->pic_mitra_phone : '') }}" required>
        <div class="invalid-feedback">Nomor telepon (partner PIC) wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="namePic2">Name (UNAIR PIC)</label>
        <input class="form-control" id="namePic2" name="namePic2" placeholder="Name (UNAIR PIC)" value = "{{ old('namePic2', isset($data) ? $data->pic_fak_nama : '') }}" required>
        <div class="invalid-feedback">Name (UNAIR PIC) wajib diisi.</div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="postPic2">Name of Position (UNAIR PIC)</label>
        <input class="form-control" id="postPic2" name="postPic2" placeholder="Name of Position (UNAIR PIC)" value = "{{ old('postPic2', isset($data) ? $data->pic_fak_jabatan : '') }}"  required>
        <div class="invalid-feedback">Name of position (UNAIR PIC) wajib diisi.</div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="emailPic2">Email (UNAIR PIC)</label>
        <input type="email" class="form-control" id="emailPic2" name="emailPic2" placeholder="Email (UNAIR PIC)" value = "{{ old('emailPic2', isset($data) ? $data->pic_fak_email : '') }}" required>
        <div class="invalid-feedback">Email (UNAIR PIC) wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="telpPic2">No. Telepon (UNAIR PIC)</label>
        <input class="form-control" id="telpPic2" name="telpPic2" type="number" placeholder="Nomor Telepon (UNAIR PIC)" value = "{{ old('telpPic2', isset($data) ? $data->pic_fak_phone : '') }}" required>
        <div class="invalid-feedback">Nomor telepon (UNAIR PIC) wajib diisi.</div>
      </div>
      
    </div>

      <div class="col-12">
  
        <button class="btn btn-primary" type="submit">Submit</button>
  
      </div>
    </form>
  </div>
</div>


<script>
  $('#rejectModal .btn-danger').on('click', function () {
    var approvalNotes = $('#approvalNotes').val(); // Ambil nilai dari textarea
    var form = $('#reject-form'); // Ambil form reject
    $('<input>').attr({
      type: 'hidden',
      name: 'approvalNotes', // Nama input yang akan dikirimkan
      value: approvalNotes // Nilai yang diambil dari modal
    }).appendTo(form);
    form.submit(); // Submit form
  });
</script>

</script>
<script>
$(document).ready(function() {
    $('#scopeP').select2({
        placeholder: 'Select Scope of Agreement',
        allowClear: true,
        multiple: true});
    $('#stuProgP').select2({
        placeholder: 'Select Study Program',
        allowClear: true,
        multiple: true});
    $('#FacP').select2({
        placeholder: 'Select Faculty',
        allowClear: true,
        multiple: true});
    $('#partnerP').select2({
        placeholder: 'Select Partner',
        allowClear: true,
        multiple: true});
});

    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
  const jenisSelect = document.getElementById('jenisP'); 
  const regSection = document.querySelector('.riset'); 
  const regInputs = regSection.querySelectorAll('input, select, textarea'); 

  if (jenisSelect && regSection) {
    jenisSelect.addEventListener('change', function () {
      if (this.value === 'Riset') {
        regSection.style.display = 'block'; 
        regInputs.forEach(input => input.setAttribute('required', ''));
      } else {
        regSection.style.display = 'none'; 
        regInputs.forEach(input => input.removeAttribute('required')); 
      }
    });
    if (jenisSelect.value === 'Riset') {
      regSection.style.display = 'block';
      regInputs.forEach(input => input.setAttribute('required', ''));
    } else {
      regSection.style.display = 'none';
      regInputs.forEach(input => input.removeAttribute('required'));
    }
  }
});
</script>
<script>
  document.getElementById("reNotes").style.display ="none";
    function openTest() {
        document.getElementById("reNotes").style.display = "block";
    }
    document.getElementById('reviseButton').addEventListener('click', openTest);
</script>
<script>
  document.getElementById("reNotes2").style.display ="none";
    function openTest() {
        document.getElementById("reNotes2").style.display = "block";
    }

    document.getElementById('rejectButton').addEventListener('click', openTest);
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@endsection

