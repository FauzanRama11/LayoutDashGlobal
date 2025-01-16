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
	                        <table class="display" id="API-2">
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
										<td><form action="" method="GET">
												<button type="submit" class="btn btn-primary edit-button">Edit</button>
											</form>
										</td>
										<td><form action="" method="GET">
												<button type="submit" class="btn btn-primary delete-button">Delete</button>
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