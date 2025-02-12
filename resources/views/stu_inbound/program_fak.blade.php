@extends('layouts.master')

@section('title')Program AGE | Student Inbound
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.0/vfs_fonts.min.js"></script>

@section('content')
    <h2>Student Inbound</h2>
    <p>Program Fakultas</p>

	
    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-16">
								@role("fakultas")
								<a href="{{ route('stuin_fak.create') }}">
									<button class="btn btn-success btn-sm active" type="button" style="width: 20%; margin:15px">+ Tambah</button>
								</a>
								@endrole
	                            <thead>	
									<tr>
										<th>No</th>
										<th>Name</th>
										<th>Started Date</th>
										<th>End Date</th>
										<th>Category</th>
										<th>Via</th>
										<th>Host Unit</th>
										<th>Full/Part Time</th>
										<th>Jumlah Pendaftar</th>
										<th>Jenis</th>
										<th>Created Time</th>
										<th>Edit</th>
										<th>Delete</th>
									</tr>
	                            </thead>
	                            <tbody>
				
	                            </tbody>
	                            <tfoot>																
									<tr>
										<th>No</th>
										<th>Name</th>
										<th>Started Date</th>
										<th>End Date</th>
										<th>Category</th>
										<th>Via</th>
										<th>Host Unit</th>
										<th>Full/Part Time</th>
										<th>Jumlah Pendaftar</th>
										<th>Jenis</th>
										<th>Created Time</th>
										<th>Edit</th>
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
   function initializeDataTable(tableId) {
	   if ($(tableId).length && !$.fn.DataTable.isDataTable(tableId)) {
	   var columnsToExport = ':visible';     
	 
   return $(tableId).DataTable({
	   order: [ ],
   
		   pageLength: 10,
		   lengthMenu: [
			   [10, 25, 100, 250, 500, 1000, -1],
			   ['10', '25', '100', '250', '500', '1000', 'All']
		   ],
   
		   processing: true,
		   serverSide: true,
		   ordering: true,
		   paging: true,
		   ajax: {
			   url: "{{ route('stuin_program_fak') }}",
			   type: 'GET'
		   },
		   columns: [
	   { data: null, sortable: false, render: function (data, type, row, meta) {
		   return meta.row + meta.settings._iDisplayStart + 1;
	   }},
		{ data: 'name', name: 'name' },
		{ data: 'start_date', name: 'start_date' },
		{ data: 'end_date', name: 'end_date' },
		{ data: 'cat', name: 'category_text' },
		{ data: 'via', name: 'via' },
		{ data: 'unit', name: 'host_unit_text' },
		{ data: 'pt_ft', name: 'pt_ft' },
		{ data: 'jumlah_peserta', name: 'jumlah_peserta' },
		{ data: 'is_private_event', name: 'is_private_event' },
		{ data: 'created_time', name: 'created_time' },
		{ data: 'edit', name: 'edit', orderable: false, searchable: false },
		{ data: 'delete', name: 'delete', orderable: false, searchable: false }
			
	   
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
   
	   if (document.getElementById('norm-16')) {
		   const tableNorm2 = initializeDataTable('#norm-16');
   
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
   @endsection