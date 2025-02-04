@extends('layouts.master') 

@section('title')Pendaftar | Amerta
@endsection

@section('content') 
 
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css')}}">
@endpush

    <h2>Student Inbound | Amerta</h2>
    <p>This is the Pendaftar</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="API-2">
	                            <thead>
	                                <tr>
	                                    <th>Full Name</th>
										<th>Email</th>
	                                    <th>Periode Program</th>
	                                    <th>Program</th>
	                                    <th>Tgl Daftar</th>
	                                    <th>Status</th>
										<th>Edit</th>
										<th>Approve</th>
										<th>Reject</th>
	                                </tr>
	                            </thead>
	                            <tbody>
									@foreach ($processedData as $item)
									<tr>
										<td>{{ $item['full_name'] ?? $item['fullname'] }}</td>
										<td>{{ $item['email'] ?? '' }}</td>
										<td>{{ $item['date_program'] ?? '' }}</td>
										<td>{{ $item['selected_program'] === 'AMERTA' ? 'Amerta' : $item['selected_program']  }}</td>
										<td>{{ $item['created_date'] ?? '' }}</td>
										<td>
											@if ($item['is_approve'] === true)
												<span class="badge badge-primary">Approved</span>
											@elseif ($item['is_approve'] === false)
												<span class="badge badge-danger">Rejected</span>
											@else
												<span class="badge badge-info">Belum diproses</span>
											@endif
										</td>
										<td>
											<form action="{{route('edit_peserta_inbound', ['id' => $item['id'] ]) }}" method="GET">
												<button type="submit" class="btn btn-primary edit-button">Edit</button>
											</form>
										</td>
										<td> 
											@if ($item['loaPeserta'])
												@if ($item['is_approve'] == true)
													<form action="{{ route('unapprove_peserta_inbound', ['id' => $item['id'] ]) }}" method="POST">
														@csrf
														@method('PUT')
														<button type="submit" class="btn btn-primary edit-button">Unapprove</button>
													</form>
												@else
													<form action="{{ route('approve_peserta_inbound', ['id' => $item['id'] ]) }}" method="POST">
														@csrf
														@method('PUT')
														<button type="submit" class="btn btn-primary edit-button">Approve</button>
													</form>
												@endif
											@endif
										</td>
										<td>
											<form action="{{ route('reject_peserta_inbound', ['id' => $item['id'] ]) }}" method="POST">
												@csrf
												@method('PUT')
												<button type="submit" class="btn btn-danger edit-button">Reject</button>
											</form>
										</td>
									</tr>
									@endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
                                        <th>Full Name</th>
										<th>Email</th>
	                                    <th>Periode Program</th>
	                                    <th>Program</th>
	                                    <th>Tgl Daftar</th>
	                                    <th>Status</th>
										<th>Edit</th>
										<th>Approve</th>
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