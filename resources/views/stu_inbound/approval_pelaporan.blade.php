@extends('layouts.master') 

@section('title')Approval Pelaporan | Student Inbound
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

	return !empty($folder)
		? route('view.dokumen', ['folder' => $folder, 'fileName' => $fileName]) 
		: route('view.dokumen', ['folder' => $fileName]);	
}

@endphp


    <h2>Student Outbound</h2>
    <p>This is Approval Pelaporan.</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-13">
	                            <thead>
	                                <tr>
										<th>No</th>
	                                    <th>Nama</th>
	                                    <th>Fakultas</th>
	                                    <th>Program</th>
	                                    <th>Part/Full Time</th>
                                        <th>Univ</th>
	                                    <th>Tanggal Mulai</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Via</th>
                                        <th>Country</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
                                        <th>Loa Status</th>
                                        <th>Approval Status</th>
										<th>Action</th>
	                                    <th>Add Revision</th>
	                                    <th>Reject</th>
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
	                                    <th>Tanggal Mulai</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Via</th>
                                        <th>Country</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
                                        <th>Loa Status</th>
                                        <th>Approval Status</th>
										<th>Action</th>
	                                    <th>Add Revision</th>
	                                    <th>Reject</th>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    let approveRoute = @json(route('stuin.approve', ['id' => '__ID__']));
    let unapproveRoute = @json(route('stuin.unapprove', ['id' => '__ID__']));
    let rejectRoute = @json(route('stuin.reject', ['id' => '__ID__']));

    function confirmApprove(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You are about to approve this participant.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Approve!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Processing...",
                    text: "Please wait while we process your request.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: approveRoute.replace('__ID__', id),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire("Success!", response.message, "success").then(() => {
                            location.reload(); // 🔄 Reload page after success
                        });
                    },
                    error: function(xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    }
                });
            }
        });
    }

    function confirmUnapprove(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You are about to unapprove this participant.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Unapprove!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Processing...",
                    text: "Please wait while we process your request.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: unapproveRoute.replace('__ID__', id),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire("Success!", response.message, "success").then(() => {
                            location.reload(); // 🔄 Reload page after success
                        });
                    },
                    error: function(xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    }
                });
            }
        });
    }

    function confirmReject(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You are about to reject this participant.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Reject!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Processing...",
                    text: "Please wait while we process your request.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: rejectRoute.replace('__ID__', id),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire("Success!", response.message, "success").then(() => {
                            if (response.redirect) {
                                window.location.href = response.redirect; // 🔄 Redirect if needed
                            } else {
                                location.reload(); // 🔄 Reload page if no redirect
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    }
                });
            }
        });
    }
</script>

<script>
	$(document).ready(function () {
		$(document).on("click", ".reviseButton", function () {
			let id = $(this).data("id");
			console.log("ID yang dikirim:", id); 

			Swal.fire({
				title: 'Revisi Data',
				input: 'text',
				inputPlaceholder: 'Masukkan catatan revisi di sini...',
				showCancelButton: true,
				confirmButtonText: '<i class="fa fa-save"></i> Simpan',
				cancelButtonText: '<i class="fa fa-times"></i> Batal',
				confirmButtonColor: "#007bff",
				cancelButtonColor: "#d33",
				inputValidator: (value) => {
					if (!value.trim()) {
						return 'Catatan revisi tidak boleh kosong!';
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					let revisionNote = result.value;
					console.log("Revisi Note:", revisionNote); 
	
					Swal.fire({
						title: 'Menyimpan...',
						text: 'Mohon tunggu sementara revisi disimpan.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
	
					$.ajax({
						url: "/student-inbound/save-revision/" + id, 
						type: "POST",
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
						},
						data: {
							revision_note: revisionNote
						},
						success: function (response) {
							console.log("Response sukses:", response); 
							Swal.fire('Berhasil!', 'Revisi berhasil disimpan.', 'success')
							.then(() => {
								location.reload();
							});
						},
						error: function (xhr) {
							console.log("Error response:", xhr.responseText); 
							Swal.fire('Error!', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
						}
					});
				}
			});
		});
	
		$(document).on("click", ".viewRevisionButton", function () {
			let revisionNote = $(this).data("revision"); 
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
				   url: "{{ route('stuin_approval_pelaporan') }}",
				   type: 'GET'
			   },
			   columns: [
		   { data: null, sortable: false, render: function (data, type, row, meta) {
			   return meta.row + meta.settings._iDisplayStart + 1;
		   }},
		   { data: 'nama', name: 't.nama' },
		   { data: 'host_unit', name: 'p.host_unit_text' },
		   { data: 'program', name: 'p.name' },
		   { data: 'tipe', name: 'p.pt_ft' },
		   { data: 'univ_name', name: 'u.name' },
		   { data: 'start_date', name: 'p.start_date' },
		   { data: 'end_date', name: 'p.end_date' },
		   { data: 'via', name: 'p.via' },
		   { data: 'country_name', name: 'c.name' },
		   { data: 'photo_url', name: 't.photo_url' },
		   { data: 'passport_url', name: 't.passport_url' },
		   { data: 'student_id_url', name: 't.student_id_url' },
		   { data: 'loa_url', name: 't.loa_url' },
		   { data: 'cv_url', name: 't.cv_url' },
		   { data: 'is_loa', name: 't.is_loa'},
		   { data: 'is_approved', name: 't.is_approved'},
		   { data: 'action', name: 'action', orderable:false, searchable:false },
		   { data: 'revise', name: 'revise',  orderable:false, searchable:false  },
		   { data: 'reject', name: 'reject',  orderable:false, searchable:false  }
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
	   
		   if (document.getElementById('norm-13')) {
			   const tableNorm2 = initializeDataTable('#norm-13');
	   
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