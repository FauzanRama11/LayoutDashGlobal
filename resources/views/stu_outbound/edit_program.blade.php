@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                <div class="card-header pb-0">
                    <h5>Edit Program</h5><span>This is Optional Notes</span></div>
                    <div class="card-body">
                    @if($data->is_private_event === "Ya")
                        <a href="{{ route('tambah.peserta', ['ids' => $data->id]) }}"><button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">+ Tambah Peserta</button></a>
                        @endif    
                    <form action="{{ route('program_stuout.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3" style="display: none;">
                                <label class="form-label" for="progAge"></label>
                                <input class="form-control" id="progAge" name="progAge" value="{{old('progAge', $data->is_program_age)}}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="jenisSelect">Jenis Program Pelaporan</label>
                                <input class="form-control" id="jenisSelect" name="jenisSelect" value="{{ old('jenisSelect', $data->is_private_event) }}" readonly>
                                <div class="invalid-feedback"></div>
                            </div>  
                                <div class="mb-3">
                                    <label class="form-label" for="nameProg">Nama Program</label>
                                    <input class="form-control" id="nameProg" name="nameProg" value= "{{ old('nameProg', $data->name) }}"  required>
                                    <div class="invalid-feedback">Nama program wajib diisi.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="startDate">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" value="{{ old('startDate', $data->start_date) }}" required>
                                    <div class="invalid-feedback">Tanggal mulai wajib diisi.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="endDate">Tanggal Berakhir</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" value= "{{ old('endDate', $data->end_date) }}" required>
                                    <div class="invalid-feedback">Tanggal berakhir wajib diisi.</div>
                                </div>
                                
                                @if($data->is_private_event === "Tidak")
                                <div class="reg">
                                    <div class="card-header pb-0">
                                    <h5>Detail Registrasi</h5><span>Detail Registrasi Program</span>
                                    </div>
                                    <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label" for="regOpen">Tanggal Registrasi Buka</label>
                                        <input type="date" class="form-control" id="regOpen" name="regOpen">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="regClose">Tanggal Registrasi Tutup</label>
                                        <input type="date" class="form-control" id="regClose" name="regClose">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="programDesc">Deskripsi Program</label>
                                        <textarea class="form-control" id="programDesc" name="programDesc">{{ old('programDesc', $data->description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="programLogo">Logo/Poster</label>
                                        <input class="form-control" type="file" id="programLogo" name="programLogo">
                                    </div>
                                    </div>
                                </div>
                                @endif

                                <div class="mb-2">
                                    <label class="form-label" for="progCategory">Kategori</label>
                                    <select class="js-example-basic-single col-sm-12" id="progCategory" name="progCategory" required>
                                        @foreach($category as $item)
                                            <option value="{{ $item->id }}" {{ old('progCategory', $data->category) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Pilih kategori.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="hostUnit">Unit Penyelenggara</label>
                                    <input class="form-control" id="hostUnit" name="hostUnit" value= "{{ old('host_unit_test', $data->host_unit_text) }}" readonly>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label" for="pic">PIC</label>
                                    <select class="js-example-basic-single col-sm-12" id="pic" name="pic">
                                    @foreach($dosen as $item)
                                        <option value="{{ $item->id }}" {{ old('pic', $data->pic) == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value = "{{old('email', $data->corresponding)}}" required>
                                    <div class="invalid-feedback">Email wajib diisi.</div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label" for="univTujuan">Universitas Tujuan</label>
                                    <select class="js-example-basic-single col-sm-12" id="univTujuan" name="univTujuan">
                                        @foreach($univ as $item)
                                            <option value="{{ $item->name }}" {{ old('univTujuan', $data->universitas_tujuan) == $item->name ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label" for="website">Website</label>
                                    <input class="form-control" id="website" name="website" value="{{old('website', $data->website)}}" required>
                                    <div class="invalid-feedback">Website wajib diisi.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="via">Via</label>
                                    <select class="form-select" id="via" name="via" required>
                                        <option value="Offline" {{ old('via', $data->via) == 'Offline' ? 'selected' : '' }}>Offline</option>
                                        <option value="Online" {{ old('via', $data->via) == 'Online' ? 'selected' : '' }}>Online</option>
                                        <option value="Hybrid" {{ old('via', $data->via) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                </div>


                            <button type="submit" class="btn btn-primary">Update Program</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
