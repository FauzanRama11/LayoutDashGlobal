@extends('layouts.master') 

@section('title')Periode | Amerta
@endsection

@section('content') 

{{-- @push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css')}}">
@endpush --}}

    <h2>Student Inbound | Amerta</h2>
    <p>This is the Periode.</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="API-2">
								<a href="{{ route('am_form_periode.create') }}">
									<button class="btn btn-success btn-sm active" type="button" style="width: 20%; margin:15px">+ Tambah</button>
								</a>
							
								<thead>
									<tr>
										<th>Tgl Mulai Pendaftaran</th>
										<th>Tgl Berakhir Pendaftaran</th>
										<th>Tgl Mulai Program</th>
										<th>Tgl Berakhir Program</th>
										@hasrole('gmp')
											<th>Edit</th>
											<th>Delete</th>
										@endrole
									</tr>
								</thead>
								<tbody>
									@foreach ($data as $item)
									<tr>
										<td>{{ \Carbon\Carbon::parse($item->start_date_pendaftaran)->format('d M Y') ?? '-' }}</td>
										<td>{{ \Carbon\Carbon::parse($item->end_date_pendaftaran)->format('d M Y') ?? '-' }}</td>
										<td>{{ \Carbon\Carbon::parse($item->start_date_program)->format('d M Y') ?? '-' }}</td>
										<td>{{ \Carbon\Carbon::parse($item->end_date_program)->format('d M Y') ?? '-' }}</td>
										@hasrole('gmp')
											<td>
												<a href="{{ route('am_form_periode.edit', ['id' => $item->id]) }}" class="btn btn-primary btn-sm">Edit</a>
											</td>
											<td>
												<form action="{{ route('am_hapus_periode', ['id' => $item->id]) }}" method="POST">
													@csrf
													@method('DELETE')
													<button type="submit" class="btn btn-danger btn-sm">Delete</button>
												</form>
											</td>
										@endrole
									</tr>
									@endforeach
									<tfoot>
										<tr>
											<th>Tgl Mulai Pendaftaran</th>
											<th>Tgl Berakhir Pandaftaran</th>
											<th>Tgl Mulai Program</th>
											<th>Tgl Berakhir Program</th>
											@hasrole('gmp')
												<th>Edit</th>
												<th>Delete</th>
											@endrole
										</tr>
									</tfoot>
								</tbody>
							</table>
							
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- Individual column searching (text inputs) Ends-->
	    </div>
	</div>
@endsection