@extends('layouts.master') 
@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <h5>Form Program AGE</h5><span>This is Optional Notes</span>
  </div>

    <form class="was-validated" action="{{ url('/store-peserta') }}" method="post" enctype="multipart/form-data">
      @csrf
        <div class="card-header pb-0">
            <h5>Umum</h5><span>Informasi Umum Peserta</span>
        </div>
        
    <div class="card-body">
      <div class="mb-3" style="display: none;">
          <label class="form-label" for="progId"></label>
          <input class="form-control" id="progId" name="progId" value="{{$ids}}" readonly>
          <div class="invalid-feedback"></div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="namePeserta">Nama</label>
        <input class="form-control" id="namePeserta" name="namePeserta" placeholder="Nama Peserta" required>
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
            <option value="Laki-Laki">Laki-Laki</option>
            <option value="Perempuan">Perempuan</option>
          </select>
        </div>
        
      <div class="mb-3">
        <label class="form-label" for="dobPeserta">Tanggal Lahir</label>
        <input type="date" class="form-control" id="dobPeserta" name="dobPeserta" required>
        <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="telpPeserta">No. Telepon</label>
        <input class="form-control" id="telpPeserta" name="telpPeserta" placeholder="Nomor Telepon" required>
        <div class="invalid-feedback">Nomor Telepon wajib diisi.</div>
      </div>

      <div class="mb-3">
        <label class="form-label" for="emailPeserta">Email</label>
        <input type="email" class="form-control" id="emailPeserta" name="emailPeserta" placeholder="Email" required>
        <div class="invalid-feedback">Email wajib diisi.</div>
      </div>

        <div class="mb-3">
          <label class="form-label" for="jenjangPeserta">Jenjang</label>
          <select class="form-select" id="jenjangPeserta" name="jenjangPeserta">
            <option value="Diploma">Diploma</option>
            <option value="Bahcelor">Bachelor</option>
            <option value="Master">Master</option>
            <option value="Doctor">Doctor</option>
          </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="tProdiPeserta">Tujuan Prodi</label>
            <input class="form-control" id="tProdiPeserta" name="tProdiPeserta" placeholder="Prodi Tujuan" required>
            <div class="invalid-feedback">Tujuan prodi wajib diisi.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="tFakultasPeserta">Tujuan Fakultas</label>
            <input class="form-control" id="tFakultasPeserta" name="tFakultasPeserta" placeholder="Faakultas Tujuan" required>
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
