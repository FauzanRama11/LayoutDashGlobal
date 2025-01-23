@extends('layouts.master') 
@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <h5>Form Program AGE</h5><span>This is Optional Notes</span>
  </div>

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
  

    <form class="was-validated" action="{{ $data ? route('stuout_peserta.update') : route('stuout_peserta.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      @if ($data)
          @method('PUT')
          <input type="hidden" name="peserta_id" value="{{ $data->id }}">
      @endif

        <div class="card-header pb-0">
            <h5>Umum</h5><span>Informasi Umum Peserta</span>
        </div>
        
    <div class="card-body">

    
        <div class="mb-3" style="display: none;">
            <label class="form-label" for="progId"></label>
            <input class="form-control" id="progId" name="progId" value="{{$prog_id}}" readonly>
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="namePeserta">Nama</label>
          <input class="form-control" id="namePeserta" name="namePeserta" placeholder="Nama Peserta"
          value="{{ old('namePeserta', $data->nama ?? '') }}" required>
          <div class="invalid-feedback">Nama wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="nimPeserta">NIM</label>
          <input class="form-control" id="nimPeserta" name="nimPeserta" placeholder="NIM Peserta" required>
          <div class="invalid-feedback">NIM wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="angkatanPeserta">Angkatan</label>
          <select class="form-select" id="angkatanPeserta" name="angkatanPeserta" required>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
            <option value="2027">2027</option>
            <option value="2028">2028</option>
            <option value="2029">2029</option>
            <option value="2030">2030</option>
          </select>
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
          <label class="form-label" for="jenjangPeserta">Jenjang</label>
          <select class="form-select" id="jenjangPeserta" name="jenjangPeserta">
            <option value="diploma" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'diploma' ? 'selected' : '' }}>Diploma</option>
            <option value="bachelor" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
            <option value="master" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'master' ? 'selected' : '' }}>Master</option>
            <option value="doctor" {{ old('jenjangPeserta', $data->jenjang ?? '') == 'doctor' ? 'selected' : '' }}>Doctor</option>
          </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="tProdiPeserta">Tujuan Prodi</label>
            <input class="form-control" id="tProdiPeserta" name="tProdiPeserta" placeholder="Prodi Tujuan" required
             value="{{ old('tProdiPeserta', $data->tujuan_prodi ?? '') }}">
            <div class="invalid-feedback">Tujuan prodi wajib diisi.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="tFakultasPeserta">Tujuan Fakultas</label>
            <input class="form-control" id="tujuan_fakultas_unit" name="tFakultasPeserta" placeholder="Faakultas Tujuan" required
            value="{{ old('tFakultasPeserta', $data->tujuan_prodi ?? '') }}">
            <div class="invalid-feedback">Tujuan fakultas wajib diisi.</div>
        </div>

        <div class="mb-2">
        <label class="form-label" for="univTujuanPeserta">Universitas Tujuan</label>
        <select class="js-example-basic-single col-sm-12" id="univTujuanPeserta" name="univTujuanPeserta">
          @foreach($univ as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
          @endforeach
        </select>
        </div>

  
        <div class="mb-3">
            <label class="form-label" for="fotoPeserta">Foto</label>
            <input class="form-control" type="file" id="fotoPeserta" name="fotoPeserta"  accept=".jpg, .jpeg, .png"  required>
            <div class="invalid-feedback">Foto wajib diisi.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="cvPeserta">CV</label>
            <input class="form-control" type="file" id="cvPeserta" name="cvPeserta"  accept=".pdf"  required>
            <div class="invalid-feedback">CV wajib diisi.</div>
        </div>
    </div>

        <div class="card-header pb-0">
          <h5>Approval</h5><span>Approval Peserta</span>
        </div>

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label" for="loaPeserta">LoA</label>
            <input class="form-control" type="file" id="loaPeserta" name="loaPeserta"  accept=".pdf" required>
            <div class="invalid-feedback">LoA wajib diisi.</div>
        </div>

        <div class="mb-2">
        <label class="form-label" for="fakultasPeserta">Fakultas</label>
        <select class="js-example-basic-single col-sm-12" id="fakultasPeserta" name="fakultasPeserta">
          @foreach($fakultas as $item)
            <option value="{{ $item->id }}">{{ $item->nama_ind }}</option>
          @endforeach
        </select>
        </div>

        <div class="mb-2">
        <label class="form-label" for="prodiPeserta">Prodi</label>
        <select class="js-example-basic-single col-sm-12" id="prodiPeserta" name="prodiPeserta">
          @foreach($prodi as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
          @endforeach
        </select>
        </div>
    </div>

        <div class="card-header pb-0">
          <h5>Imigrasi</h5><span>Imigrasi Peserta</span>
        </div>
    
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label" for="noPassPeserta">Nomor Passport</label>
            <input class="form-control" id="noPassPeserta" name="noPassPeserta" placeholder="Nomor Passport" required>
            <div class="invalid-feedback">Nomor passport wajib diisi.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="homePeserta">Home Address</label>
            <textarea class="form-control" id="homePeserta" name="homePeserta" placeholder="Home Address Peserta" required></textarea>
            <div class="invalid-feedback">Nomor passport wajib diisi.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="passPeserta">Passport</label>
            <input class="form-control" type="file" id="passPeserta" name="passPeserta"   accept=".jpg, .jpeg, .png"  required>
            <div class="invalid-feedback">Passport wajib diisi.</div>
        </div>
    </div>

      <div class="col-12">
        <button class="btn btn-primary" type="submit">Submit</button>
      </div>
    </form>

</div>
@endsection
