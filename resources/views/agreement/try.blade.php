@extends('layouts.master') 

@section('content') 
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#norm-2').DataTable({
            processing: true,
            serverSide: true,
            sortable: true,
            searchable: true,
            ajax: '/try',
            columns: [
                { data: 'country', name: 'country' },
                { data: 'fakultas', name: 'fakultas' },
                { data: 'department_unair', name: 'department_unair' },
                { data: 'partner_involved', name: 'partner_involved' },
                { data: 'prodi_involved', name: 'prodi_involved' },
                { data: 'faculty_involved', name: 'faculty_involved' },
                { data: 'collaboration_scope', name: 'collaboration_scope' }
            ]
        });
    });
</script>
<div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
<table id="norm-2" class="display" data-columns-export=":not(:eq(0)):not(:eq(1)):not(:gt(44))">
<div class="row align-items-center justify-content-between mb-4">
    <thead>
        <tr>
            <th>Country</th>
            <th>Fakultas</th>
            <th>Department</th>
            <th>Partner Involved</th>
            <th>Prodi Involved</th>
            <th>Faculty Involved</th>
            <th>Collaboration Scope</th>
        </tr>
    </thead>
    <tbody>
        <!-- DataTable will populate here -->
    </tbody>
    <tfoot>
        <tr>
            <th>Country</th>
            <th>Fakultas</th>
            <th>Department</th>
            <th>Partner Involved</th>
            <th>Prodi Involved</th>
            <th>Faculty Involved</th>
            <th>Collaboration Scope</th>
        </tr>
    </tfoot>
</div>
</table>
</div>
	                </div>
	            </div>
	        </div>
	        <!-- Individual column searching (text inputs) Ends-->
	    </div>
	</div>
@endsection