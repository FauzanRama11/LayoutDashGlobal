@extends('layouts.master') 

@section('content') 
    <h2>Student Outbound</h2>
    <p>This is the Target.</p>
    
 <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="API-2">
							<a href="{{ route('form_target_out.create') }}">
									<button class="btn btn-success btn-sm active" type="button" style="width: 20%; margin:15px">+ Tambah</button>
								</a>
	                            <thead>
	                                <tr>
	                                    <th>No.</th>
	                                    <th>Year</th>
	                                    <th>Fakultas/Unit</th>
	                                    <th>Target Part Time</th>
	                                    <th>Target Full Time</th>
	                                    <th>Edit</th>
                                        <th>Delete</th>
	                                </tr>
	                            </thead>
	                            <tbody>
									@foreach ($data as $index => $item)
									<tr>
										<td>{{ $index + 1 }}</td>
										<td>{{ $item->year ?? '-' }}</td>
										<td>{{ $item->fakultas_name ?? '-' }}</td>
										<td>{{ $item->target_pt ?? '-' }}</td>
										<td>{{ $item->target_ft ?? '-' }}</td>                                
										<td>
											<form  action="{{ route('form_target_out.edit', ['id' => $item->id]) }}" method="GET">
												<button type="submit" class="btn btn-success edit-button">Edit</button>
											</form>
										</td>
										<td>
											<form action="{{ route('hapus_target_out', ['id' => $item->id]) }}" method="POST">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger">Delete</button>
											</form>
										</td>


									</tr>
									@endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
                                        <th>No.</th>
	                                    <th>Year</th>
	                                    <th>Fakultas/Unit</th>
	                                    <th>Target Part Time</th>
	                                    <th>Target Full Time</th>
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
@endsection