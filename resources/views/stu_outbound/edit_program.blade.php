@extends('layouts.master')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        function generatePdf(tipe) {
            let pesertaRKATCount = {{ $pesertaRKAT->count() }};
            let pesertaDPATCount = {{ $pesertaDPAT->count() }};
    
            if ((tipe === 'RKAT' && pesertaRKATCount > 0) || (tipe === 'DPAT' && pesertaDPATCount > 0)) {
                let url = "{{ route('stuout.pengajuan.dana', ['id' => '__ID__', 'tipe' => '__TIPE__']) }}";
                url = url.replace('__ID__', "{{ $data->id }}").replace('__TIPE__', tipe);
                window.open(url, "_blank");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Pemberitahuan",
                    text: "Tidak ada peserta dengan sumber dana " + tipe + ".",
                    confirmButtonColor: "#007bff"
                });
            }
        }
    
        // Klik tombol RKAT
        $('#generatePdfRkat').click(function () {
            generatePdf('RKAT');
        });
    
        // Klik tombol DPAT
        $('#generatePdfDpat').click(function () {
            generatePdf('DPAT');
        });
    });
</script>
    

@php										
function getFileUrl($fileUrl) {
												
	if (empty($fileUrl)) return null;

		$filePath = ltrim(str_replace('repo/', '', $fileUrl), '/');
		$segments = explode('/', $filePath);
		$fileName = array_pop($segments);
		$folder = implode('/', $segments);

	return !empty($folder)
		? route('view.dokumen', ['folder' => $folder, 'fileName' => $fileName]) 
		: route('view.dokumen', ['folder' => $fileName]);	
}

@endphp

@section('content')




    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Detail Program Student Outbound</h5>
                            <span>This is Optional Notes</span>
                        </div>
                  
                   
                        @if ($data)
                          <div class="d-flex">
                  
                            <a href="" class="btn btn-info mx-1">Back</a>
                            <button type="button" id="generatePdfRkat" class="btn btn-secondary mx-1">PDF RKAT</button>
                            <button type="button" id="generatePdfDpat" class="btn btn-secondary mx-1">PDF DPAT</button>
                  
                          </div>
                        @endif
                        
                    </div>
                    <hr>

                    <div class="card-body">
                    <form action="{{ route('program_stuout.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3" style="display: none;">
                                        <label class="form-label" for="progAge"></label>
                                        <input class="form-control" id="progAge" name="progAge" value="{{old('progAge', $data->is_program_age)}}">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="jenisSelect">Jenis Program Pelaporan</label>
                                        <input class="form-control" id="jenisSelect" name="jenisSelect" value="{{ old('jenisSelect', $data->is_private_event === 'Ya' ? 'Pelaporan': 'Registrasi') }}" readonly>
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
                                </div>
                                
                                <div class="col-md-6">
                                    @if($data->is_private_event === "Tidak")
                                    <div class="reg">
                                        <div class="card-header pb-0">
                                        <h5>Detail Registrasi</h5><span>Detail Registrasi Program</span>
                                        </div>
                                        <div class="card-body">
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

                                        <div class="mt-3">
                                            <div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="previewdiv" style="display: {{ $data->logo_base64 ? 'block' : 'none' }};">
                                                @if($data->logo_base64)
                                                    <img id="preview" src="{{ $data->logo_base64 }}" alt="Preview" class="img-fluid" style="max-width: 100%; height: 300px; object-fit: cover;">
                                                @else
                                                    <p id="noLogoMessage">Logo tidak tersedia.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label" for="url_generate">Link Registrasi</label>
                                            <input class="form-control" id="url_generate" name="url_generate" value="http://127.0.0.1:8000/registrasi-peserta-outbound/{{ old('url_generate', $data->url_generate) }}" readonly>
                                        </div>
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
                            
                            @if($data->is_private_event == "Ya")
                                @if($data->is_program_age == "N")
                                    @hasrole('fakultas')
                                        <a href="{{ route('stuout_peserta.create', ['prog_id' => $data->id]) }}">
                                            <button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">
                                                + Tambah Peserta
                                            </button>
                                        </a>
                                    @endhasrole
                                @else
                                    @hasrole('gmp')
                                        <a href="{{ route('stuout_peserta.create', ['prog_id' => $data->id]) }}">
                                            <button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">
                                                + Tambah Peserta
                                            </button>
                                        </a>
                                    @endhasrole
                                @endif
                            @endif    
							
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
                                    <th>Prodi Tujuan</th>
                                    <th>Fakultas Tujuan</th>
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
                                            <td>  
                                                @if ($item->is_approved === 1)
                                                    <button class="btn btn-success btn-sm" disabled>Approved</button>
                                                @elseif ($item->is_approved === -1)
                                                    <button class="btn btn-danger btn-sm" disabled>Rejected</button>
                                                    @if (!empty($item->revision_note)) 
                                                        <!-- Tombol untuk melihat revisi -->
                                                        <button type="button" class="btn btn-warning btn-sm viewRevisionButton"
                                                            data-revision="{{ $item->revision_note }}">
                                                            <i class="fa fa-eye"></i> 
                                                        </button>
                                                    @endif
                                                @else
                                                    <button class="btn btn-info btn-sm" disabled>Processed</button>
                                                    @if (!empty($item->revision_note)) 
                                                        <!-- Tombol untuk melihat revisi -->
                                                        <button type="button" class="btn btn-warning btn-sm viewRevisionButton"
                                                            data-revision="{{ $item->revision_note }}">
                                                            <i class="fa fa-eye"></i> 
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->pengajuan_dana_status === 'APPROVED')
                                                    <button class="btn btn-success btn-sm" disabled>Approved  [ {{ $item->sumber_dana ?? ''}}]</button>
                                                @elseif ($item->pengajuan_dana_status === 'REQUESTED')
                                                    <button class="btn btn-warning btn-sm" disabled>Requesting</button>
                                                @else
                                                    <button class="btn btn-info btn-sm" disabled>Not Requesting</button>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($url = getFileUrl($item->loa_url))
                                                    <a href="{{ $url }}" target="_blank" class="btn btn-primary">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <form  action="{{ route('stuout_peserta.edit', ['item_id' => $item->id, 'prog_id' => $data->id]) }}" method="GET">
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
                                    <th>Prodi Tujuan</th>
                                    <th>Fakultas Tujuan</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $(document).on("click", ".viewRevisionButton", function () {
            let revisionNote = $(this).data("revision"); // Ambil data revisi dari tombol
            console.log("Isi revisi:", revisionNote); // Debugging

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
@endsection
