@extends('layouts.master') 

@section('content') 
    <h2>Student Outbound</h2>
    <p>This is the Pengajuan Setneg.</p>

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
                                        <th>No</th>
	                                    <th>Nama</th>
	                                    <th>Program</th>
	                                    <th>Country</th>
	                                    <th>Started Date</th>
                                        <th>Fakultas</th>
	                                    <th>Prodi</th>
										<th>Status</th>
	                                    <th>See Documment</th>
										<th>Download</th>
	                                </tr>
	                            </thead>
	                            <tbody>
									@foreach ($data as $item)
									<tr>
										<td>{{ $item->id ?? '-' }}</td>
										<td>{{ $item->nama ?? '-' }}</td>
										<td>{{ $item->program ?? '-' }}</td>
										<td>{{ $item->country ?? '-' }}</td>
										<td>{{ $item->start_date ?? '-' }}</td>
										<td>{{ $item->fakultas ?? '-' }}</td>
										<td>{{ $item->prodi ?? '-' }}</td>
										<td>{{ $item->status ?? '-' }}</td>
										<td><form action="" method="GET">
												<button type="submit" class="btn btn-primary edit-button">See</button>
											</form>
										</td>	
										<td><form action="" method="GET">
												<button type="submit" class="btn btn-primary edit-button">Download</button>
											</form>
										</td>
									</tr>
									@endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
                                        <th>No</th>
	                                    <th>Nama</th>
	                                    <th>Program</th>
	                                    <th>Country</th>
	                                    <th>Started Date</th>
                                        <th>Fakultas</th>
	                                    <th>Prodi</th>
										<th>Status</th>
	                                    <th>See Documment</th>
										<th>Download</th>
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