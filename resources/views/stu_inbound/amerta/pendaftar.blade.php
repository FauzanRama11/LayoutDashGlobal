@extends('layouts.master') 

@section('title')Pendaftar | Amerta
@endsection

@section('content') 

    <h2>Student Inbound | Amerta</h2>
    <p>This is the Pendaftar</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-1">
	                            <thead>
	                                <tr>
	                                    <th>Full Name</th>
										<th>Email</th>
	                                    <th>Periode Program</th>
	                                    <th>Program</th>
	                                    <th>Tgl Daftar</th>
	                                    <th>Status</th>
										@hasrole('gmp')
										<th>Edit</th>
										<th>Approve</th>
										<th>Reject</th>
										@endrole
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
										
										@hasrole('gmp')
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
											@else
												@if ($item['is_approve'] == true)
														<form action="{{ route('unapprove_peserta_inbound', ['id' => $item['id'] ]) }}" method="POST">
															@csrf
															@method('PUT')
															<button type="submit" class="btn btn-primary edit-button">Unapprove</button>
														</form>
												@else
												<button class="btn btn-primary edit-button" disabled>Upload Loa First</button>
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
										@endrole
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
										@hasrole('gmp')
										<th>Edit</th>
										<th>Approve</th>
										<th>Reject</th>
										@endrole
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
{{-- 
@if ($processedData['loaPeserta'])
                @if ($processedData['is_approve'] === true)
                    <button type="button" id="unapproveButton" class="btn btn-warning">
                        <i class="fa fa-times-circle"></i> Unapprove
                    </button>
                @else
                    
                    <button type="button" id="approveButton" class="btn btn-success">
                        <i class="fa fa-check-circle"></i> Approve
                    </button>

                    <button type="button" id="rejectButton" class="btn btn-danger">
                        <i class="fa fa-ban"></i> Reject
                    </button>
                @endif
            @endif

 --}}
