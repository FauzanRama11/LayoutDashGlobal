@extends('layouts.master') 

@section('content') 
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.0/vfs_fonts.min.js"></script>

<div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">

	                        <table class="display" id="norm-3" data-columns-export=":not(:eq(0)):not(:eq(1)):not(:gt(44))">
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
                                                <button class="btn btn-secondary" id="btn-toggle-date-range" onclick="toggleDateRange()">Between</button>
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
                                                            <div class="mb-3 flex-fill">
                                                                <label for="start-date" class="form-label text-white">From:</label>
                                                                <input type="date" id="start-date" class="form-control">
                                                            </div>
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
<script>
 
 $(document).ready(function () {
    console.log('Initializing DataTables...');

    window.toggleDateRange = function () {
    const dateRangeElement = document.getElementById('date-range');
    if (dateRangeElement) {
        dateRangeElement.classList.toggle('d-none'); 
    } else {
        console.error('Element with ID "date-range" not found.');
    }
};

document.addEventListener('click', function (event) {
    const dateRangeElement = document.getElementById('date-range');
    const toggleButton = document.getElementById('btn-toggle-date-range');

    if (
        dateRangeElement &&
        !dateRangeElement.classList.contains('d-none') &&
        !dateRangeElement.contains(event.target) &&
        !toggleButton.contains(event.target)
    ) {
        dateRangeElement.classList.add('d-none');
    }
});

    function initializeDataTable(tableId) {
        if ($(tableId).length && !$.fn.DataTable.isDataTable(tableId)) {
    var columnsToExport = ':visible';     
  
return $(tableId).DataTable({
    order: [ ],

        pageLength: 25,
        lengthMenu: [
            [25, 50, 100, 250, 500, 1000, -1],
            ['25', '50', '100', '250', '500', '1000', 'All']
        ],

        processing: true,
        serverSide: true,
        ordering: true,
        paging: true,
        ajax: {
            url: "{{ route('view_database') }}",
            type: 'GET'
        },
        columns: [
            { data: null,   sortable: false, render: function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
    }},
            { data: 'country', name: 'c.name'},
            { data: 'partner_involved', name: 'partner_involved', orderable:false},
            { data: 'jenis_naskah', name: 'jenis_naskah'},
            { data: 'title', name: 'title', orderable: true,},
            { data: 'link_download_naskah', name: 'link_download_naskah' },
            { data: 'mou_start_date', name: 'mou_start_date' },
            { data: 'mou_end_date', name: 'mou_end_date' },
            { data: 'status', name: 'status' },
            { data: 'status_lapkerma', name: 'status_lapkerma' },
            { data: 'category_document', name: 'category_document' },
            { data: 'skema', name: 'skema' },
            { data: 'link_partnership_profile', name: 'link_partnership_profile' },
            { data: 'year', name: 'year' },
            { data: 'age_archive_sn', name: 'age_archive_sn' },
            { data: 'lapkerma_archive', name: 'lapkerma_archive' },
            { data: 'website_lapkerma', name: 'website_lapkerma' },
            { data: 'fakultas', name: 'f.nama_eng' },
            { data: 'department_unair', name: 'dep.nama_eng' },
            { data: 'prodi_involved', name: 'prodi_involved', orderable:false},
            { data: 'faculty_partner', name: 'faculty_partner' },
            { data: 'department_partner', name: 'department_partner' },
            { data: 'program_study_partner', name: 'program_study_partner' },
            { data: 'type_institution_partner', name: 'type_institution_partner' },
            { data: 'region', name: 'region' },
            { data: 'type_grant', name: 'type_grant' },
            { data: 'source_funding', name: 'source_funding' },
            { data: 'sum_funding', name: 'sum_funding' },
            { data: 'signatories_unair_name', name: 'signatories_unair_name' },
            { data: 'signatories_unair_pos', name: 'signatories_unair_pos' },
            { data: 'signatories_partner_name', name: 'signatories_partner_name' },
            { data: 'signatories_partner_pos', name: 'signatories_partner_pos' },
            { data: 'collaboration_scope', name: 'collaboration_scope',  orderable:false},
            { data: 'pic_mitra_nama', name: 'pic_mitra_nama' },
            { data: 'pic_mitra_jabatan', name: 'pic_mitra_jabatan' },
            { data: 'pic_mitra_email', name: 'pic_mitra_email' },
            { data: 'pic_mitra_phone', name: 'pic_mitra_phone' },
            { data: 'pic_fak_nama', name: 'pic_fak_nama' },
            { data: 'pic_fak_jabatan', name: 'pic_fak_jabatan' },
            { data: 'pic_fak_email', name: 'pic_fak_email' },
            { data: 'pic_fak_phone', name: 'pic_fak_phone' },
            { data: 'faculty_involved', name: 'faculty_involved', orderable:false },
            { data: 'area_collab', name: 'area_collab' },
            { data: 'add_bukti', name: 'add_bukti', orderable: false},   
            { data: 'status_pelaporan_lapkerma', name: 'status_pelaporan_lapkerma' },
            { data: 'link_pelaporan', name: 'link_pelaporan' },
            @hasrole('gpc')
            { data: 'edit', name: 'edit', orderable: false, searchable: false },
            { data: 'delete', name: 'delete', orderable: false, searchable: false },
            @endhasrole
        ],
        dom: '<"top"lBf>rt<"bottom"ip><"clear">',
        buttons: [
            {
                extend: 'csv',
                text: 'Export CSV',
                className: 'btn btn-success btn-sm active ms-4',
                exportOptions: {
                    columns: columnsToExport,  // Specify which columns to export
                    modifier: {
                        search: 'applied',  // Only export filtered data
                        order: 'applied',   // Maintain the order applied in the table
                        page: 'current'     // Export only the current page
                    }
                }
            }
        ],
             columnDefs: [
                    { orderable: true, targets: 3 }
                ],    
                initComplete: function () {
                    console.log(`Setting up search inputs for ${tableId}`);
                    $(`${tableId} tfoot th`).each(function () {
                        const title = $(this).text();
                        if ($(this).length) {
                            $(this).html(`<input type="text" placeholder="Search ${title}" />`);
                        }
                    });

                    this.api().columns().every(function () {
                        const that = this;
                        $('input', this.footer()).on('keyup change', function () {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
                }
    });
}
 return null;
};

    if (document.getElementById('norm-3')) {
        const tableNorm2 = initializeDataTable('#norm-3');

        if (tableNorm2) {
            console.log('DataTable "example" initialized successfully.');

            // Filter tanggal custom menggunakan DataTables
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                const startDateInput = document.getElementById('start-date')?.value || null;
                const endDateInput = document.getElementById('end-date')?.value || null;

                if (!startDateInput && !endDateInput) {
                    return true;
                }

                const row = tableNorm2.row(dataIndex).node();
                const rowStartDate = row?.getAttribute('data-start-date')
                    ? new Date(row.getAttribute('data-start-date'))
                    : new Date(data[3]); 

                if (isNaN(rowStartDate.getTime())) {
                    return false; 
                }

                const startDate = startDateInput ? new Date(startDateInput) : null;
                const endDate = endDateInput ? new Date(endDateInput) : null;

                return (
                    (!startDate || rowStartDate >= startDate) &&
                    (!endDate || rowStartDate <= endDate)
                );

                console.log(startDate)
            });

            window.applyBetweenFilter = function () {
                console.log('Applying date filter...');
                tableNorm2.draw();
            };

            // Tambahkan event listener pada tombol eksternal untuk eksport
            $('#export-excel').on('click', function () {
                if (tableNorm2) {
                    tableNorm2.button('.buttons-excel').trigger();
                } else {
                    console.error('Table is not initialized for export.');
                }
            });
        }
    }
});

 </script>
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
@endsection
