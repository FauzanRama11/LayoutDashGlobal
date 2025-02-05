@extends('layouts.master') 

@section('title')Approval Dana | Student Inbound
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.0/vfs_fonts.min.js"></script>

@section('content') 

@php										
function getFileUrl($fileUrl) {
												
	if (empty($fileUrl)) return null;

		$filePath = ltrim(str_replace('repo/', '', $fileUrl), '/');
		$segments = explode('/', $filePath);
		$fileName = array_pop($segments);
		$folder = implode('/', $segments);

		$encodedFileName = urlencode($fileName);
		$encodedFolder = urlencode($folder);

	return !empty($folder)
		? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
		: route('view.dokumen', ['folder' => $encodedFileName]);	
}

@endphp

    <h2>Student Inbound</h2>
    <p>This is Approval Dana.</p>
    
 	<div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-12">
	                            <thead>
	                                <tr>
										<th>No</th>
	                                    <th>Nama</th>
	                                    <th>Fakultas</th>
	                                    <th>Program</th>
	                                    <th>Part/Full Time</th>
                                        <th>Univ</th>
	                                    <th>Negara Asal</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
										<th>Sumber Dana</th>
                                        <th>Status</th>
	                                    <th>Action</th>
	                                </tr>
	                            </thead>
	                            <tbody>

	                            </tbody>
	                            <tfoot>
	                                <tr>
										<th>No</th>
	                                    <th>Nama</th>
	                                    <th>Fakultas</th>
	                                    <th>Program</th>
	                                    <th>Part/Full Time</th>
                                        <th>Univ</th>
	                                    <th>Negara Asal</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
										<th>Sumber Dana</th>
                                        <th>Status</th>
	                                    <th>Action</th>
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
function initializeDataTable(tableId) {
	if ($(tableId).length && !$.fn.DataTable.isDataTable(tableId)) {
    var columnsToExport = ':visible';     
  
return $(tableId).DataTable({
    order: [ ],

        pageLength: 10,
        lengthMenu: [
            [10, 25, 100, 250, 1000, -1],
            ['10', '25', '100', '250', '1000', 'All']
        ],

        processing: true,
        serverSide: true,
        ordering: true,
        paging: true,
        ajax: {
            url: "{{ route('stuin_approval_dana') }}",
            type: 'GET'
        },
		columns: [
    { data: null, sortable: false, render: function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
    }},
    { data: 'nama', name: 'm_stu_in_peserta.nama' },
    { data: 'fakultas', name: 'm_fakultas_unit.nama_ind' },
    { data: 'program', name: 'm_stu_in_programs.name'},
    { data: 'tipe', name: 'm_stu_in_programs.pt_ft'},
    { data: 'univ', name: 'm_university.name' },
    { data: 'negara_asal_univ', name: 'm_country.name' },
    { data: 'photo_url', name: 'photo_url' },
    { data: 'passport_url', name: 'passport_url' },
	{ data: 'student_id_url', name: 'student_id_url' },
	{ data: 'loa_url', name: 'loa_url' },
    { data: 'cv_url', name: 'cv_url' },
	{ data: 'sumber_dana', name: 'sumber_dana'},
	{ data: 'pengajuan_dana_status', name: 'pengajuan_dana_status' },
    { data: 'action', name: 'action' }
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

    if (document.getElementById('norm-12')) {
        const tableNorm2 = initializeDataTable('#norm-12');

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
        }
    }
 });
</script>
@endsection