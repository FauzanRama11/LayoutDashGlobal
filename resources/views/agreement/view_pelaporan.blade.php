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
    <h2>Pelaporan</h2>
    <p>This is Pelaporan.</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-4" data-columns-export=":not(:eq(0)):not(:gt(7))">
                            @role("wadek3")
								<a href= "form-pelaporan"><button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">+ Tambah</button></a>
	                        @endrole   
	                            <thead>
	                                <tr>
	                                    <th>No.</th>
	                                    <th>Tittle of Agreement</th>
	                                    <th>University/Institutions</th>
	                                    <th>Fakultas Pengaju</th>
	                                    <th>Type of Document</th>
	                                    <th>Approval</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Created Date</th>
                                        <th>See</th>
										<th>Delete</th>
	                                </tr>
	                            </thead>
	                            <tbody>
                               
	                            </tbody>
	                            <tfoot>
	                            <tr>
	                                <th>No.</th>
	                                <th>Tittle of Agreement</th>
	                                <th>University/Institutions</th>
	                                <th>Fakultas Pengaju</th>
	                                <th>Type of Document</th>
	                                <th>Approval</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Created Date</th>
                                    <th>See</th>
									<th>Delete</th>
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
            if (dateRangeElement.classList.contains('d-none')) {
                dateRangeElement.classList.remove('d-none');
            } else {
                dateRangeElement.classList.add('d-none');
            }
        } else {
            console.error('Element with ID "date-range" not found.');
        }
    };

    document.addEventListener('click', function (event) {
        const dateRangeElement = document.getElementById('date-range');
        const toggleButton = document.querySelector('.btn-secondary');

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
            url: "{{ route('view_pelaporan') }}",
            type: 'GET'
        },
        columns: [
            { data: null,   sortable: false, render: function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
    }},
            { data: 'title', name: 'title'},
            { data: 'partner', name: 'fac.partner'},
            { data: 'fakultas', name: 'f.nama_ind'},
            { data: 'jenis_naskah', name: 'jenis_naskah'},
            { data: 'approval_pelaporan', name: 'approval_pelaporan'},
            { data: 'mou_start_date', name: 'mou_start_date' },
            { data: 'mou_end_date', name: 'mou_end_date' },
            { data: 'created_date', name: 'status' },
            { data: 'edit', name: 'edit', orderable: false, searchable: false },
            { data: 'delete', name: 'delete', orderable: false, searchable: false },
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

    if (document.getElementById('norm-4')) {
        const tableNorm2 = initializeDataTable('#norm-4');

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
