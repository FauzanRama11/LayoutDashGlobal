@extends('layouts.master') 

@section('content') 
    <h2>Database Agreement</h2>
    <p>This is the Database Agreement.</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-2" data-columns-export=":not(:eq(0)):not(:eq(1)):not(:gt(44))">
                                <div class="row align-items-center justify-content-between mb-4">
                                    <!-- Tombol Tambah di Kiri -->
                                    <div class="col-auto">
                                        @role("gpc")
                                            <a href= "form-master-database">
                                                <button class="btn btn-success btn-sm active" type="button"  style="padding:8px 44px">+ Tambah</button>
                                            </a>
                                        @endrole   
                                    </div>
                               
                                    <!-- Tombol Filter di Kanan -->
                                    <div class="col-auto">
                                        <div class="btn-group" role="group" aria-label="Filter Tanggal">
                                            <div class="position-relative">
                                                <button class="btn btn-secondary" onclick="toggleDateRange()">Between</button>
                               
                                                <!-- Pop-up Input untuk Filter Between -->
                                                <div class="position-absolute bg-dark rounded p-3 shadow d-none" id="date-range" style="z-index: 1050; right: 0; top: 40px;">
                                                    <div class="d-flex flex-column">
                                                        <!-- Pilihan Filter -->
                                                        <div class="mb-3">
                                                            <label for="filter-type" class="form-label text-white">Filter By:</label>
                                                            <select id="filter-type" class="form-select">
                                                                <option value="start-date">Start Date</option>
                                                                <option value="end-date">End Date</option>
                                                            </select>
                                                        </div>

                                                        <!-- Input Tanggal -->
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <!-- Input From -->
                                                            <div class="mb-3 flex-fill">
                                                                <label for="start-date" class="form-label text-white">From:</label>
                                                                <input type="date" id="start-date" class="form-control">
                                                            </div>
                                                            
                                                            <!-- Input To -->
                                                            <div class="mb-3 flex-fill">
                                                                <label for="end-date" class="form-label text-white">To:</label>
                                                                <input type="date" id="end-date" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-success w-100" onclick="applyBetweenFilter()">Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Country</th>
                                    <th>University / Institutions</th>
                                    <th>Type of Document</th>
                                    <th>Title of Agreement</th>
                                    <th>Link Download Naskah</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Active / Expired Per 12/01/2025</th>
                                    <th>Status Lapkerma</th>
                                    <th>Category of Document</th>
                                    <th>Skema</th>
                                    <th>Link Partnership Profile</th>
                                    <th>Year</th>
                                    <th>AGE Archive Serial Number</th>
                                    <th>Lapkerma Serial Number</th>
                                    <th>Website Lapkerma</th>
                                    <th>Faculty / Center / Unit (UNAIR)</th>
                                    <th>Department (UNAIR)</th>
                                    <th>Program Studi (UNAIR)</th>
                                    <th>Faculty / Center / Unit (Partners)</th>
                                    <th>Department (Partners)</th>
                                    <th>Program Studi (Partners)</th>
                                    <th>Type of Institution (Partners)</th>
                                    <th>Region</th>
                                    <th>Type of Grant</th>
                                    <th>Source of Funding</th>
                                    <th>Sum of Funding</th>
                                    <th>Nama Penandatangan</th>
                                    <th>Designation (Jabatan)</th>
                                    <th>Nama Penandatangan Mitra</th>
                                    <th>Designation of Partner Signatory</th>
                                    <th>Collaboration Scope</th>
                                    <th>Name of Partner PIC</th>
                                    <th>Position of Partner PIC</th>
                                    <th>Email of Partner PIC</th>
                                    <th>Telephone / WA of Partner PIC</th>
                                    <th>Name of UNAIR PIC</th>
                                    <th>Position of UNAIR PIC</th>
                                    <th>Email of UNAIR PIC</th>
                                    <th>Telephone / WA of UNAIR PIC</th>
                                    <th>Involved Faculty at UNAIR</th>
                                    <th>Status Upload Pelaporan</th>
                                    <th>Upload Pelaporan</th>
                                    <th>Status Upload Pelaporan Lapkerma</th>
                                    <th>Download Pelaporan</th>
                                    @hasrole('gpc')
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    @endhasrole
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($merged as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->country }}</td>
                                        <td>{{ $item->partner_involved}}</td> <!-- Update for the 'University / Institutions' -->
                                        <td>{{ $item->jenis_naskah }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            <a href="{{ route('view_naskah.pdf', basename($item->link_download_naskah)) }}" target="_blank"  class="btn btn btn-primary"><i class="fa fa-download"></i></a>
                                            <span style="display: none;">{{ route('view_naskah.pdf', basename($item->link_download_naskah)) }}</span>
                                        </td> 
                                        <td>{{ $item->mou_start_date }}</td>
                                        <td>@if ($item->mou_end_date != "0000-00-00")
												{{$item->mou_end_date}}
											@else
												{{ $item->text_end_date }}
											@endif
                                        </td>
                                        <td>
                                        @if ($item->mou_end_date != "0000-00-00" && \Carbon\Carbon::parse($item->mou_end_date)->isPast())
                                            <button type="submit" class="btn btn-danger btn-sm">Expired</button>
                                        @elseif ($item->mou_end_date != "0000-00-00")
                                            <button type="submit" class="btn btn-success btn-sm">Active</button>
                                        @else
                                            <button type="submit" class="btn btn-success btn-sm">Active</button>
                                        @endif
                                        </td> 
                                        <td>
                                            @if ($item->status_lapkerma == "SUDAH")
                                                <button type="submit" class="btn btn-success btn-sm">Sudah</button>
                                            @elseif ($item->status_lapkerma == "BELUM")
                                                <button type="submit" class="btn btn-danger btn-sm">Belum</button>
                                            @else
                                                <button type="submit" class="btn btn-warning btn-sm">Unknown</button>
                                            @endif
                                        </td>
                                        <td>{{ $item->category_document }}</td>
                                        <td>{{ $item->skema }}</td>
                                        <td><a href="{{ $item->link_partnership_profile }}" target="_blank">View Profile</a></td>
                                        <td>{{ $item->year }}</td>
                                        <td>{{ $item->age_archive_sn }}</td>
                                        <td>{{ $item->lapkerma_archive }}</td>
                                        <td><a href="{{ $item->website_lapkerma }}" target="_blank">Visit Website</a></td>
                                        <td>{{ $item->fakultas }}</td>
                                        <td>{{ $item->department_unair }}</td>
                                        <td>{{ $item->prodi_involved }}</td>
                                        <td>{{ $item->faculty_partner }}</td>
                                        <td>{{ $item->department_partner }}</td>
                                        <td>{{ $item->program_study_partner }}</td>
                                        <td>{{ $item->type_institution_partner }}</td>
                                        <td>{{ $item->region }}</td>
                                        <td>{{ $item->type_grant }}</td>
                                        <td>{{ $item->source_funding }}</td>
                                        <td>{{ $item->sum_funding }}</td>
                                        <td>{{ $item->signatories_unair_name }}</td>
                                        <td>{{ $item->signatories_unair_pos }}</td>
                                        <td>{{ $item->signatories_partner_name }}</td>
                                        <td>{{ $item->signatories_partner_pos }}</td>
                                        <td>{{ $item->collaboration_scope }}</td>
                                        <td>{{ $item->pic_mitra_nama }}</td>
                                        <td>{{ $item->pic_mitra_jabatan }}</td>
                                        <td>{{ $item->pic_mitra_email }}</td>
                                        <td>{{ $item->pic_mitra_phone }}</td>
                                        <td>{{ $item->pic_fak_nama }}</td>
                                        <td>{{ $item->pic_fak_jabatan }}</td>
                                        <td>{{ $item->pic_fak_email }}</td>
                                        <td>{{ $item->pic_fak_phone }}</td>
                                        <td>{{ $item->faculty_involved }}</td>
                                        <th>
                                            @if (!empty($item->link_pelaporan))
                                                <button type="submit" class="btn btn-success btn-sm">Sudah</button>
                                            @else
                                                <button type="submit" class="btn btn-danger btn-sm">Belum</button>
                                            @endif
                                        </th>
                                        <td>
                                        @if ($item->created_by == Auth::user()->id || Auth::user()->hasRole('gpc'))
                                            <form action="{{ route('bukti.upload', ['id' => $item->id]) }}" method="GET">
                                                <button class="btn btn-warning btn-sm" 
                                                        type="submit" 
                                                        data-toggle="tooltip" 
                                                        data-placement="top" 
                                                        title="Upload">
                                                    <i class="fa fa-upload"></i>
                                                </button>
                                            </form>
                                        @endif
                                        </td>
                                        <th>
                                            @if ($item->status_pelaporan_lapkerma == "Sudah")
                                                <button type="submit" class="btn btn-success btn-sm">Sudah</button>
                                            @elseif ($item->status_pelaporan_lapkerma == "Belum")
                                                <button type="submit" class="btn btn-danger btn-sm">Belum</button>
                                            @else
                                                <button type="submit" class="btn btn-warning btn-sm">Unknown</button>
                                            @endif
                                        </th>
                                        <td>
                                            @if (!empty($item->link_pelaporan))
                                                    <a href="{{ route('view_naskah.pdf', basename($item->link_pelaporan)) }}" target="_blank"  class="btn btn btn-primary"><i class="fa fa-download"></i></a>
                                                   <span style="display: none;">{{ route('view_naskah.pdf', basename($item->link_pelaporan)) }}</span>
                                            @endif
                                        </td>
                                        @hasrole('gpc')
                                        <td>
                                            <form action="{{ route('master_database.edit', ['id' => $item->id]) }}" method="GET">
                                                <button class="btn btn-success btn-sm" 
                                                        type="submit" 
                                                        data-toggle="tooltip" 
                                                        data-placement="top" 
                                                        title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form id="deleteForm{{ $item->id }}" action="{{ route('database_agreement.destroy', ['id' => $item->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm mr-2" onclick="confirmDelete('{{ $item->id }}', 'Data yang telah dihapus tidak dapat dipulihkan')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>                                                                              
                                        @endhasrole
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Country</th>
                                        <th>University / Institutions</th>
                                        <th>Type of Document</th>
                                        <th>Title of Agreement</th>
                                        <th>Link Download Naskah</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Active / Expired Per 12/01/2025</th>
                                        <th>Status Lapkerma</th>
                                        <th>Category of Document</th>
                                        <th>Skema</th>
                                        <th>Link Partnership Profile</th>
                                        <th>Year</th>
                                        <th>AGE Archive Serial Number</th>
                                        <th>Lapkerma Serial Number</th>
                                        <th>Website Lapkerma</th>
                                        <th>Faculty / Center / Unit (UNAIR)</th>
                                        <th>Department (UNAIR)</th>
                                        <th>Program Studi (UNAIR)</th>
                                        <th>Faculty / Center / Unit (Partners)</th>
                                        <th>Department (Partners)</th>
                                        <th>Program Studi (Partners)</th>
                                        <th>Type of Institution (Partners)</th>
                                        <th>Region</th>
                                        <th>Type of Grant</th>
                                        <th>Source of Funding</th>
                                        <th>Sum of Funding</th>
                                        <th>Nama Penandatangan</th>
                                        <th>Designation (Jabatan)</th>
                                        <th>Nama Penandatangan Mitra</th>
                                        <th>Designation of Partner Signatory</th>
                                        <th>Collaboration Scope</th>
                                        <th>Name of Partner PIC</th>
                                        <th>Position of Partner PIC</th>
                                        <th>Email of Partner PIC</th>
                                        <th>Telephone / WA of Partner PIC</th>
                                        <th>Name of UNAIR PIC</th>
                                        <th>Position of UNAIR PIC</th>
                                        <th>Email of UNAIR PIC</th>
                                        <th>Telephone / WA of UNAIR PIC</th>
                                        <th>Involved Faculty at UNAIR</th>
                                        <th>Status Upload Pelaporan</th>
                                        <th>Upload Pelaporan</th>
                                        <th>Status Upload Pelaporan Lapkerma</th>
                                        <th>Download Pelaporan</th>
                                        @hasrole('gpc')
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        @endhasrole
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


<!-- SweetAlert2  -->
<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

function confirmDelete(itemId, message) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus data!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form untuk menghapus data
            const form = document.getElementById(`deleteForm${itemId}`);
            if (form) {
                $.ajax({
                    url: form.action, // URL diambil dari atribut action form
                    type: 'POST', // Laravel mengharuskan POST untuk DELETE
                    data: $(form).serialize(), // Kirim data form (CSRF token, dll.)
                    success: function (response) {
                        if (response.status === 'success') {
                            // Tampilkan pesan SweetAlert2 jika berhasil
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data telah terhapus.',
                                icon: 'success',
                                timer: 4000,
                                showConfirmButton: false
                            }).then(() => {
                                // Refresh halaman atau hapus baris dari tabel
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message || 'Tidak dapat menghapus data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Form penghapusan tidak ditemukan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}


</script>