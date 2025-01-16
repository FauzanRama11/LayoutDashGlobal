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
                            <a href="{{ route('stuin_peserta.create', ['ids' => $data->id]) }}"><button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">+ Tambah Peserta</button></a>
                        @endif    
                    <form action="{{ route('program_stuin.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3" style="display: none;">
                                        <label class="form-label" for="progAge"></label>
                                        <input class="form-control" id="progAge" name="progAge" value="{{ old('progAge', $data->is_program_age) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label" for="jenisSelect">Jenis Program Pelaporan</label>
                                        <input class="form-control" id="jenisSelect" name="jenisSelect" value="{{ old('jenisSelect', $data->is_private_event === 'Ya' ? 'Pelaporan': 'Registrasi') }}" readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>  
                            
                                    <div class="mb-3">
                                        <label class="form-label" for="nameProg">Nama Program</label>
                                        <input class="form-control" id="nameProg" name="nameProg" value="{{ old('nameProg', $data->name) }}" required>
                                        <div class="invalid-feedback">Nama program wajib diisi.</div>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label" for="startDate">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="startDate" name="startDate" value="{{ old('startDate', $data->start_date) }}" required>
                                        <div class="invalid-feedback">Tanggal mulai wajib diisi.</div>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label" for="endDate">Tanggal Berakhir</label>
                                        <input type="date" class="form-control" id="endDate" name="endDate" value="{{ old('endDate', $data->end_date) }}" required>
                                        <div class="invalid-feedback">Tanggal berakhir wajib diisi.</div>
                                    </div>
                            
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
                                        <input class="form-control" id="hostUnit" name="hostUnit" value="{{ old('host_unit_test', $data->host_unit_text) }}" readonly>
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
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $data->corresponding) }}" required>
                                        <div class="invalid-feedback">Email wajib diisi.</div>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label" for="website">Website</label>
                                        <input class="form-control" id="website" name="website" value="{{ old('website', $data->website) }}" required>
                                        <div class="invalid-feedback">Website wajib diisi.</div>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label" for="type">Type</label>
                                        <select class="form-select" id="type" name="type" required>
                                            <option value="Offline" {{ old('type', $data->type) == 'PT' ? 'selected' : '' }}>PT (Part Time)</option>
                                            <option value="Online" {{ old('type', $data->type) == 'FT' ? 'selected' : '' }}>FT (Full Time)</option>
                                        </select>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label" for="via">Via</label>
                                        <select class="form-select" id="via" name="via" required>
                                            <option value="Offline" {{ old('via', $data->via) == 'Offline' ? 'selected' : '' }}>Offline</option>
                                            <option value="Online" {{ old('via', $data->via) == 'Online' ? 'selected' : '' }}>Online</option>
                                            <option value="Hybrid" {{ old('via', $data->via) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                            
                                    @if($data->is_private_event === "Tidak")
                                    <div class="reg">
                                        <div class="mb-3">
                                            <label class="form-label" for="regOpen">Tanggal Registrasi Buka</label>
                                            <input type="date" class="form-control" id="regOpen" name="regOpen" value="{{ old('regOpen', $data->reg_date_start) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="regClose">Tanggal Registrasi Tutup</label>
                                            <input type="date" class="form-control" id="regClose" name="regClose" value="{{ old('regClose', $data->reg_date_closed) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="programDesc">Deskripsi Program</label>
                                            <textarea class="form-control" id="programDesc" name="programDesc">{{ old('programDesc', $data->description) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="programLogo">Logo/Poster</label>
                                            <input class="form-control" type="file" id="programLogo" name="programLogo" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                                        </div>
                                        
                                        <div class="mt-3">
                                            <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="previewdiv" style="display: {{ $data->logo_base64 ? 'block' : 'none' }};">
                                                @if($data->logo_base64)
                                                    <img id="preview" src="{{ $data->logo_base64 }}" alt="Preview" class="img-fluid" style="max-width: 100%; height: 300px; object-fit: cover;">
                                                @else
                                                    <p id="noLogoMessage">Logo tidak tersedia.</p>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="mb-3">
                                            <label class="form-label" for="programLogo">Logo/Poster</label>
                                            <input class="form-control" type="file" id="programLogo" name="programLogo" accept=".jpg, .jpeg, .png" >
                                        </div>
                                        <div class="mt-3">
                                            <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="previewdiv" style="display: block;">
                                                @if($data->logo_base64)
                                                    <img src="{{ $data->logo_base64 }}" alt="Preview" class="img-fluid" style="max-width:100%; height: 300px; object-fit: cover;">
                                                @else
                                                    <p>Logo tidak tersedia.</p>
                                                @endif
                                            </div>
                                        </div> --}}
                                        <div class="mt-3">
                                            <label class="form-label" for="url_generate">Link Registrasi</label>
                                            <input class="form-control" id="url_generate" name="url_generate" value="http://127.0.0.1:8000/registrasi-peserta-inbound/{{ old('url_generate', $data->url_generate) }}" readonly>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Update Program</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
							
	                        <table class="display" id="API-2">   
							<thead>
	                            <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tgl Lahir</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Jenjang</th>
                                    <th>Prodi Asal</th>
                                    <th>Fakultas Asal</th>
                                    <th>Universitas</th>
                                    <th>Negara</th>
                                    <th>Waktu Registrasi</th>
                                    <th>Status Persetujuan</th>
                                    <th>Status Pengajuan Dana</th>
                                    <th>LOA URL</th>
                                    <th>Aksi</th>
	                            </tr>
	                        </thead>
	                            <tbody>
                                    @foreach ($peserta as $item )	    
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$item->nama}}</td>
                                            <td>{{$item->jenis_kelamin}}</td>
                                            <td>{{$item->tgl_lahir}}</td>
                                            <td>{{$item->telp}}</td>
                                            <td>{{$item->email}}</td>
                                            <td>{{$item->jenjang}}</td>
                                            <td>{{$item->prodi_asal}}</td>
                                            <td>{{$item->fakultas_asal}}</td>
                                            <td>{{$item->univ_name}}</td>
                                            <td>{{$item->country_name}}</td>
                                            <td>{{$item->reg_time}}</td>
                                            <td>{{$item->is_approved}}</td>
                                            <td>{{$item->pengajuan_dana_status}}</td>
                                            <td>{{$item->loa_url}}</td>
                                            <td><form action="" method="GET">
                                                    <button type="submit" class="btn btn-primary edit-button">Edit</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tgl Lahir</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Jenjang</th>
                                    <th>Prodi Asal</th>
                                    <th>Fakultas Asal</th>
                                    <th>Universitas</th>
                                    <th>Negara</th>
                                    <th>Waktu Registrasi</th>
                                    <th>Status Persetujuan</th>
                                    <th>Status Pengajuan Dana</th>
                                    <th>LOA URL</th>
                                    <th>Aksi</th>
	                                </tr>
	                            </tfoot>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- Individual column searching (text inputs) Ends-->
	    </div>
	</div>
@endsection

<script>
    function previewImage(event) {
        const input = event.target; // Input elemen file
        const previewDiv = document.getElementById('previewdiv'); // Div untuk preview
        const previewImg = document.getElementById('preview'); // Elemen gambar preview
        const noLogoMessage = document.getElementById('noLogoMessage'); // Pesan jika logo tidak tersedia

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            // Validasi tipe file
            if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                reader.onload = function (e) {
                    previewImg.src = e.target.result; // Ganti src gambar
                    previewImg.style.display = 'block'; // Tampilkan gambar
                    previewDiv.style.display = 'block'; // Tampilkan div container
                    if (noLogoMessage) noLogoMessage.style.display = 'none'; // Sembunyikan pesan "Logo tidak tersedia"
                };
                reader.readAsDataURL(file);
            } else {
                alert('File harus berupa gambar (JPG, JPEG, atau PNG).');
                input.value = ''; // Reset input file
                previewImg.style.display = 'none'; // Sembunyikan gambar
                if (noLogoMessage) noLogoMessage.style.display = 'block'; // Tampilkan pesan "Logo tidak tersedia"
            }
        } else {
            // Reset ke keadaan awal jika file dihapus dari input
            previewImg.src = '';
            previewImg.style.display = 'none';
            if (noLogoMessage) noLogoMessage.style.display = 'block';
        }
    }
</script>
