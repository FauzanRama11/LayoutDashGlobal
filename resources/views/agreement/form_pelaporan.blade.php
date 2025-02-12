@extends('layouts.master') 

@section('content') 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<div class="card">
  <div class="card-header pb-0">
    <h5>Form Pelaporan</h5><span>This is Form Pelaporan</span>
  </div>

    <div class="card-body">
    @if(isset($data))

            <div class="d-flex justify-content-start" style="margin-bottom: 15px;">
            @hasrole("gpc")
              @if(isset($data) && isset($data->approval_pelaporan) && $data->approval_pelaporan != 1)
              <form id="approveForm{{ $data->id }}" style="margin: 5px;" action="{{ route('pelaporan.approve', ['id' => $data->id]) }}" method="POST">
                @csrf
                @method('PUT') 
                <button type="submit" class="btn btn-primary mr-2" onclick="confirmAction('{{ $data->id }}', 'approve', 'Approve Data', event)"><i class="fa fa-check"></i> Approve</button>
            </form>
                      <!-- <button style="margin: 5px;" type="submit" id = "rejectButton" class="btn btn-danger mr-2">Reject</button> -->
                      <button style="margin: 5px;" type="button" class="btn btn-danger mr-2 rejectButton" data-id="{{$data->id}}"><i class="fa fa-close"></i> Reject</button>
                      <button style="margin: 5px;" type="button" class="btn btn-warning mr-2 reviseButton" data-id="{{$data->id}}"><i class="fa fa-edit"></i> Revise</button>
              @endif

              @endhasrole
              @if(isset($data) && isset($data->approval_note))
                <button style="margin: 5px;"  type="button" class="btn btn-info btn-sm viewRevisionButton" data-revision="<?= htmlspecialchars($data->approval_note); ?>">
                  <i class="fa fa-eye"></i> Notes</button>
              @endif      
          </div>

      
    @endif

    
     
 
        
    <form id="pelaporanForm" class="was-validated" action="{{ isset($data) ? route('pelaporan.update', $data->id) : route('pelaporan.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmSubmission(event)">
        @csrf
        @if(isset($data))
            @method('PUT') <!-- Untuk update, menambahkan method PUT -->
        @endif

        <div class="row">
          <!-- Kolom Kiri -->
          <div class="col-md-6">

      <div class="mb-3">
        <label class="form-label" for="jenisP">Type of Agreement</label>
        <select class="form-select" id="jenisP" name="jenisP" required>
          <option value="" selected disabled>Type of Agreement</option>
            <option value="General" {{ old('jenisP', isset($data->tipe_moa) ? $data->tipe_moa : '') == 'General' ? 'selected' : '' }}>General</option>
            <option value="Riset" {{ old('jenisP', isset($data->tipe_moa) ? $data->tipe_moa : '') == 'Riset' ? 'selected' : '' }}>Riset</option>
        </select>
        <div class="invalid-feedback">Type of Agreement wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="triDharma">Kategori Tri Dharma</label>
        <select class="form-select" id="triDharma" name="triDharma" required>
          <option value="" selected disabled>Kategori Tri Dharma</option>
            <option value="Pendidikan" {{ old('triDharma', isset($data->kategori_tridharma) ? $data->kategori_tridharma : '') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
            <option value="Riset" {{ old('triDharma', isset($data->kategori_tridharma) ? $data->kategori_tridharma : '') == 'Riset' ? 'selected' : '' }}>Riset</option>
            <option value="Pengabdian Masyarakat" {{ old('triDharma', isset($data->kategori_tridharma) ? $data->kategori_tridharma : '') == 'Pengabdian Masyarakat' ? 'selected' : '' }}>Pengabdian Masyarakat</option>
        </select>
        <div class="invalid-feedback">Kategori Tri Dharma wajib diisi.</div>
      </div>

      <div class="mb-2">
        <label class="form-label" for="unitP">Faculty/Unit (UNAIR) Pengaju</label>
        <select class="js-example-basic-single col-sm-12 form-select" id="unitP" name="unitP" required>
          <option value="" selected disabled>Faculty/Unit (UNAIR) Pengaju</option>
          @foreach($unit as $item)
            <option value="{{ $item->id }}" {{ old('unitP', isset($data->id_fakultas) ? $data->id_fakultas : '') == $item->id ? 'selected' : '' }}>{{ $item->nama_ind }}</option>
          @endforeach
        </select>
        <div class="invalid-feedback">Faculty/Unit (UNAIR) Pengaju wajib diisi.</div>
      </div>
      
    <div class="mb-2">
        <label class="form-label" for="countryP">Country</label>
        <select class="js-example-basic-single col-sm-12 form-select" id="countryP" name="countryP" required>
        <option value="" selected disabled>Country</option>
            @foreach($country as $item)
                <option value="{{ $item->id }}" {{ old('countryP', $data->id_country ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Country wajib diisi.</div>
    </div>

    <div class="mb-2">
        <label class="form-label" for="partnerP">University Partner</label>
        <select class="js-example-placeholder-multiple col-sm-12" id="partnerP" name="partnerP[]" multiple="multiple">
            @foreach ($univ as $item)
                <option value="{{ $item->id }}" 
                    {{ in_array($item->id, old('partnerP', $selPartners ?? [])) ? 'selected' : '' }}>
                    {{ $item->name }} - QS WUR By Subject 2024 : {{ $item->rank_value_min }} ({{ $item->subject }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="docP">Type of Document</label>
        <select class="form-select" id="docP" name="docP" required>
        <option value="" selected disabled>Type of Document</option>
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
          <label class="form-label" for="scopeP">Scope of Agreement</label>
          <select class="js-example-placeholder-multiple col-sm-12" id="scopeP" name="scopeP[]" multiple="multiple">
              @foreach ($scope as $item)
                  <option value="{{ $item->id }}" 
                      {{ in_array($item->id, old('scopeP', $selScopes ?? [])) ? 'selected' : '' }}>
                      {{ $item->name }}
                  </option>
              @endforeach
          </select>

      </div>

        <div class="mb-3">
          <label class="form-label" for="linkDownload">Agreement</label>
            @if (isset($data) && !empty($data->link_download_naskah))
              @if(strtotime($data->created_date) >= strtotime("2025-01-09 14:54:42"))
                <div class="mb-2">
                    <a href="{{ route('view_naskah.pdf', basename($data->link_download_naskah)) }}" target="_blank" class="btn btn-primary">
                      View / Download Document
                    </a>
                </div>   
              @else   
                <div class="mb-2">
                    <a href="{{$data->link_download_naskah}}" target="_blank" class="btn btn-primary">
                      View / Download Document
                    </a>
                </div>        
              @endif
                  <input class="form-control" type="file" name="linkDownload" accept=".pdf" onchange="validateFileSize(this)">
                  <div class="mb-2">
                    <small>Current file: {{ basename($data->link_download_naskah) }}</small>
                  </div>
              
            @else
              <input class="form-control" type="file" name="linkDownload" accept=".pdf" onchange="validateFileSize(this)" required>
            @endif
            <div class="invalid-feedback">Agreement wajib diisi.</div>
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
      </div>
        <div class="col-md-6">


      <div class="mb-3">
        <label class="form-label" for="deptP">Department</label>
        <select  class="js-example-basic-single col-sm-12 form-select" id="deptP" name="deptP" required>
        <option value="" selected disabled>Department</option>
            @foreach ($department as $item)
                <option value="{{$item -> id}}"  {{ old('deptP', isset($data->id_department_unair) ? $data->id_department_unair : '') == $item->id ? 'selected' : '' }}>{{$item->nama_ind}}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Department wajib diisi.</div>
      </div>

      <div class="mb-2">
            <label class="form-label" for="FacP">Faculty</label>
            <select class="js-example-placeholder-multiple col-sm-12" id="FacP" name="FacP[]" multiple="multiple">
        @foreach ($unit as $item)
            <option value="{{ $item->id }}"  {{ in_array($item->id, old('FacP', $selFaculties ?? [])) ? 'selected' : '' }}>
          {{ $item->nama_ind }}</option>
        @endforeach
        </select>
        </div>

        <div class="mb-2">
            <label class="form-label" for="stuProgP">Study Program</label>
            <select class="js-example-placeholder-multiple col-sm-12" id="stuProgP" name="stuProgP[]"  multiple="multiple">
            @foreach ($prodi as $item)
                <option value="{{ $item->id }}"  {{ in_array($item->id, old('stuProgP', $selProdis ?? [])) ? 'selected' : '' }}>{{ $item->level }} {{ $item->name }}</option>
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
        <option value="" selected disabled>Type of Partner</option>
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
    <div class="invalid-feedback">Type of Partner wajib diisi.</div>
</div>

<!-- Conditional Fields for Riset -->

    <div class="riset">
        <!-- Source of Funding -->
        <div class="mb-3">
            <label class="form-label" for="sourceFund">Source of Funding</label>
            <select class="form-select" id="sourceFund" name="sourceFund" required>
            <option value="" selected disabled>Source of Funding</option>
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
        <div class="mb-3">
            <label class="form-label" for="sumFund">Sum of Funding</label>
            <div class="input-group"><span class="input-group-text" id="inputGroupPrepend">Rp</span>
                <input class="form-control" id="sumFund" type="number" name="sumFund" placeholder="Sum of Fund (Rp)" aria-describedby="inputGroupPrepend" value="{{ old('sumFund', isset($data->sum_funding) ? $data->sum_funding : '') }}" required>
                <div class="invalid-feedback">Sum of fund wajib diisi</div>
            </div>
        </div>
    </div>
  </div>
</div>


      <div class="card-header pb-0">
          <h5>Penandatanganan</h5><span>Informasi Penandatanganan</span>
        </div>
      <div class="card-body">
        
      <div class="row">
          <!-- Kolom Kiri -->
          <div class="col-md-6">
        
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
    </div>
    <div class="col-md-6">
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
    </div>
      </div>

    <div class="card-header pb-0">
          <h5>Person in Charge (PIC)</h5><span>Informasi PIC</span>
        </div>
      <div class="card-body">
      <div class="row">
          <!-- Kolom Kiri -->
          <div class="col-md-6">
        
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
    </div>

    <div class="col-md-6">

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
      </div>
      



      <div class="col-12">
      @if(isset($data->approval_pelaporan))
        @if( isset($data->approval_pelaporan) && $data->approval_pelaporan != 1)
          <button class="btn btn-primary" type="submit">Submit</button>
        @else
          @hasrole('gpc')
            <button class="btn btn-primary" type="submit">Submit</button>
          @endhasrole
        @endif
      @else
        <button class="btn btn-primary" type="submit">Submit</button>
      @endif
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
        function validateFileSize(input) {
                const file = input.files[0];
                if (file) {
                    const maxSize = 2.5 * 1024 * 1024; // 1 MB
                    if (file.size > maxSize) {
                        Swal.fire({
                            title: 'File too large!',
                            text: 'The file size exceeds 2.5 MB. Please upload a smaller file.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        input.value = ""; // Clear the input
                        isFileValid = false; // Tandai file tidak valid
                    } else {
                        isFileValid = true; // Tandai file valid
                    }
                }
            }
</script>
<script>
  $(document).ready(function () {
		$(document).on("click", ".reviseButton", function () {
			let id = $(this).data("id");
			console.log("ID yang dikirim:", id); 

			Swal.fire({
				title: 'Rejection Notes',
				input: 'text',
				inputPlaceholder: 'Masukkan catatan tolak di sini...',
				showCancelButton: true,
				confirmButtonText: '<i class="fa fa-save"></i> Simpan',
				cancelButtonText: '<i class="fa fa-times"></i> Batal',
				confirmButtonColor: "#007bff",
				cancelButtonColor: "#d33",
				inputValidator: (value) => {
					if (!value.trim()) {
						return 'Catatan tidak boleh kosong!';
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					let revisionNote = result.value;
					console.log("Revisi Note:", revisionNote); 
	
					Swal.fire({
						title: 'Menyimpan...',
						text: 'Mohon tunggu sementara revisi disimpan.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
	
					$.ajax({
						url: "{{ route('pelaporan.revise', ':id') }}".replace(':id', id), 
						type: "PUT",
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
						},
						data: {
							revision_note: revisionNote
						},
						success: function (response) {
							console.log("Response sukses:", response); 
							Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success')
							.then(() => {
								location.reload();
							});
						},
						error: function (xhr) {
							console.log("Error response:", xhr.responseText); 
							Swal.fire('Error!', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
						}
					});
				}
			});
		});

    $(document).on("click", ".rejectButton", function () {
			let id = $(this).data("id");
			console.log("ID yang dikirim:", id); 

			Swal.fire({
				title: 'Notes',
				input: 'text',
				inputPlaceholder: 'Masukkan catatan revisi di sini...',
				showCancelButton: true,
				confirmButtonText: '<i class="fa fa-save"></i> Simpan',
				cancelButtonText: '<i class="fa fa-times"></i> Batal',
				confirmButtonColor: "#007bff",
				cancelButtonColor: "#d33",
				inputValidator: (value) => {
					if (!value.trim()) {
						return 'Catatan revisi tidak boleh kosong!';
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					let revisionNote = result.value;
					console.log("Revisi Note:", revisionNote); 
	
					Swal.fire({
						title: 'Menyimpan...',
						text: 'Mohon tunggu sementara revisi disimpan.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
	
					$.ajax({
						url: "{{ route('pelaporan.reject', ':id') }}".replace(':id', id), 
						type: "PUT",
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
						},
						data: {
							revision_note: revisionNote
						},
						success: function (response) {
							console.log("Response sukses:", response); 
							Swal.fire('Berhasil!', 'Revisi berhasil disimpan.', 'success')
							.then(() => {
								location.reload();
							});
						},
						error: function (xhr) {
							console.log("Error response:", xhr.responseText); 
							Swal.fire('Error!', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
						}
					});
				}
			});
		});
	
		$(document).on("click", ".viewRevisionButton", function () {
			let revisionNote = $(this).data("revision"); 
			Swal.fire({
				title: 'Revisi',
				text: revisionNote,
				icon: 'info',
				confirmButtonText: 'Tutup',
				confirmButtonColor: "#007bff"
			});
		});
	});
</script>

<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmAction(itemId, actionType, message, event) {
    event.preventDefault(); // Prevent default form submission
    const actionMessages = {
        approve: 'Are you sure you want to approve this data?',
        reject: 'Are you sure you want to reject this data?',
        revise: 'Are you sure you want to revise this data?'
    };

    Swal.fire({
        title: 'Are you sure?',
        text: actionMessages[actionType],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Execute only after confirmation
            const form = document.getElementById(`${actionType}Form${itemId}`);
            if (form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: `Data has been ${actionType}d.`,
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
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while processing the data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Form not found.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}
function confirmSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = event.target; // Get the form element
    const action = form.action.includes('update') ? 'update' : 'store'; // Determine the action
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
            // Use AJAX to submit the form
            $.ajax({
                url: form.action,
                type: form.method,
                data: new FormData(form), // Use FormData to handle file uploads
                processData: false, // Prevent jQuery from processing data
                contentType: false, // Prevent jQuery from setting content type
                success: function (response) {
                    console.log(response); // Log the response for debugging
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: `Data has been ${action === 'update' ? 'updated' : 'saved'}.`,
                            icon: 'success',
                            timer: 4000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.redirect; // Redirect after showing success message
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
                    console.error(xhr); // Log error response for debugging
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'An error occurred while processing the data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}
</script>

@endsection

