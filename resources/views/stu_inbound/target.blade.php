@extends('layouts.master') 

@section('title')Target | Student Inbound
@endsection

@section('content') 
    <h2>Student Inbound</h2>
    <p>This is the Target.</p>
	
    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-1">
								<a href="{{ route('form_target.create') }}">
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
									@foreach ($data as $item)
									<tr>
										<td>{{ $item->id ?? '-' }}</td>
										<td>{{ $item->year ?? '-' }}</td>
										<td>{{ $item->fakultas_name ?? '-' }}</td>
										<td>{{ $item->target_pt ?? '-' }}</td>
										<td>{{ $item->target_ft ?? '-' }}</td>                                
										<td>
											<a href="{{ route('form_target.edit', ['id' => $item->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>  Edit</a>
										</td>
										<td>
											<form action="{{ route('hapus_target', ['id' => $item->id]) }}" method="POST">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>  Delete</button>
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