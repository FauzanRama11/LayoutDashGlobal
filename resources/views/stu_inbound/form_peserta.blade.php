@extends('layouts.master') 
@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <h5>Form Program </h5>
    <span>This is Optional Notes</span>
  </div>

  <form class="was-validated" action="{{ route('stuin_peserta.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    <!-- Informasi Umum -->
    <div class="card-header pb-0">
      <h5>Umum</h5>
      <span>Informasi Umum Peserta</span>
    </div>
    <div class="card-body">
      <input type="hidden" id="progId" name="progId" value="{{$ids}}">

      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="namePeserta">Nama</label>
            <input class="form-control" id="namePeserta" name="namePeserta" placeholder="Nama Peserta" required>
            <div class="invalid-feedback">Nama wajib diisi.</div>
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
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="jenjangPeserta">Jenjang</label>
            <select class="form-select" id="jenjangPeserta" name="jenjangPeserta">
              <option value="diploma">Diploma</option>
              <option value="bachelor">Bachelor</option>
              <option value="master">Master</option>
              <option value="doctor">Doctor</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="tProdiPeserta">Prodi Asal</label>
            <input class="form-control" id="tProdiPeserta" name="tProdiPeserta" placeholder="Prodi Tujuan" required>
            <div class="invalid-feedback">Prodi wajib diisi.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="tFakultasPeserta">Fakultas Asal</label>
            <input class="form-control" id="tFakultasPeserta" name="tFakultasPeserta" placeholder="Fakultas Asal" required>
            <div class="invalid-feedback">Fakultas wajib diisi.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="univTujuanPeserta">Universitas</label>
            <select class="form-select" id="univTujuanPeserta" name="univTujuanPeserta">
              @foreach($univ as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian Approval -->
    <div class="card-header pb-0">
      <h5>Approval</h5>
      <span>Approval Peserta</span>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="loaPeserta">LoA</label>
            <input class="form-control" type="file" id="loaPeserta" name="loaPeserta" accept=".pdf" required>
            <div class="invalid-feedback">LoA wajib diisi.</div>
          </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="fakultasPeserta">Fakultas Tujuan</label>
            <select class="form-select" id="fakultasPeserta" name="fakultasPeserta">
              @foreach($fakultas as $item)
                <option value="{{ $item->id }}">{{ $item->nama_ind }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="prodiPeserta">Prodi Tujuan</label>
            <select class="form-select" id="prodiPeserta" name="prodiPeserta">
              @foreach($prodi as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian Imigrasi -->
    <div class="card-header pb-0">
      <h5>Imigrasi</h5>
      <span>Imigrasi Peserta</span>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="noPassPeserta">Nomor Passport</label>
            <input class="form-control" id="noPassPeserta" name="noPassPeserta" placeholder="Nomor Passport" required>
            <div class="invalid-feedback">Nomor Passport wajib diisi.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="homePeserta">Home Address</label>
            <textarea class="form-control" id="homePeserta" name="homePeserta" placeholder="Home Address" required></textarea>
            <div class="invalid-feedback">Alamat wajib diisi.</div>
          </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label" for="passPeserta">Passport</label>
            <input class="form-control" type="file" id="passPeserta" name="passPeserta" accept=".jpg, .jpeg, .png" required>
            <div class="invalid-feedback">Passport wajib diisi.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tombol Submit -->
    <div class="card-footer text-end">
      <button class="btn btn-primary" type="submit">Submit</button>
    </div>
  </form>
</div>

@endsection
