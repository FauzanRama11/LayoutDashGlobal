@extends('layouts.master')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.0/vfs_fonts.min.js"></script>

<h2>Student Inbound</h2>
<p>This is View Peserta.</p>

<div class="container-fluid">
    <div class="row">
        <!-- Individual column searching (text inputs) Starts -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="norm-15">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Unit Kerja</th>
                                    <th>Jenjang</th>
                                    <th>Prodi Asal</th>
                                    <th>Fakultas Asal</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Via</th>
                                    <th>Full/Part Time</th>
                                    <th>Tujuan Fakultas</th>
                                    <th>Tujuan Prodi</th>
                                    <th>Jenjang Prodi</th>
                                    <th>Univ Asal</th>
                                    <th>Negara Asal</th>
                                    <th>Program</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Created Date</th>
                                    <th>Foto</th>
                                    <th>Passport</th>
                                    <th>Student ID</th>
                                    <th>LoA</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Unit Kerja</th>
                                    <th>Jenjang</th>
                                    <th>Prodi Asal</th>
                                    <th>Fakultas Asal</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Via</th>
                                    <th>Full/Part Time</th>
                                    <th>Tujuan Fakultas</th>
                                    <th>Tujuan Prodi</th>
                                    <th>Jenjang Prodi</th>
                                    <th>Univ Asal</th>
                                    <th>Negara Asal</th>
                                    <th>Program</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Created Date</th>
                                    <th>Foto</th>
                                    <th>Passport</th>
                                    <th>Student ID</th>
                                    <th>LoA</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                  
                </div>
            </div>
        </div>
        <!-- Individual column searching (text inputs) Ends -->
    </div>
</div>


<script>

$(document).ready(function () {

    console.log($('#norm-15').length ? "Tabel ditemukan!" : "Tabel TIDAK ditemukan!");
    console.log(typeof $.fn.DataTable !== "undefined" ? "✅ DataTables tersedia!" : "❌ DataTables TIDAK tersedia!");


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
               url: "{{ route('stuin_view_peserta') }}",
               type: 'GET'
           },
           columns: [
       { data: null, sortable: false, render: function (data, type, row, meta) {
           return meta.row + meta.settings._iDisplayStart + 1;
       }},
       { data: 'nama', name: 'nama' },
       { data: 'unit_kerja_text', name: 'unit_kerja_text' },
       { data: 'jenjang', name: 'jenjang' },
       { data: 'prodi_asal', name: 'prodi_asal' },
       { data: 'fakultas_asal', name: 'fakultas_asal' },
       { data: 'jenis_kelamin', name: 'jenis_kelamin' },
       { data: 'via', name: 'via' },
       { data: 'tipe_text', name: 'tipe_text' },
       { data: 'tujuan_fakultas_unit_text', name: 'tujuan_fakultas_unit_text' },
       { data: 'tujuan_prodi_text', name: 'tujuan_prodi_text' },
       { data: 'jenjang_prodi', name: 'jenjang_prodi' },
       { data: 'univ_asal_text', name: 'univ_asal_text' },
       { data: 'negara_asal_text', name: 'negara_asal_text' },
       { data: 'program_text', name: 'program_text' },
       { data: 'jenis_kegiatan_text', name: 'jenis_kegiatan_text' },
       { data: 'created_date', name: 'created_date' },
       { data: 'foto', name: 'foto' },
       { data: 'passport', name: 'passport' },
       { data: 'student_id', name: 'student_id' },
       { data: 'loa', name: 'loa' }
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
   
       if (document.getElementById('norm-15')) {
           const tableNorm2 = initializeDataTable('#norm-15');
   
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