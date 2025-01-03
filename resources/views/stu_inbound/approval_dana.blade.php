@extends('layouts.master') 

@section('title')Approval Dana | Student Inbound
@endsection

@section('content') 
    <h2>Student Inbound</h2>
    <p>This is Approval Dana.</p>
    
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
	                                    <th>Fakultas</th>
	                                    <th>Program</th>
	                                    <th>Part/Full Time</th>
                                        <th>Univ</th>
	                                    <th>Negara Asal</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
                                        <th>Status</th>
	                                    <th>Approve</th>
	                                </tr>
	                            </thead>
	                            <tbody>
									@foreach ($data as $item)
									<tr>
										<td>{{ $item->id ?? '-' }}</td>
										<td>{{ $item->nama ?? '-' }}</td>
										<td>{{ $item->fakultas ?? '-' }}</td>
										<td>{{ $item->program ?? '-' }}</td>
										<td>{{ $item->tipe ?? '-' }}</td>
										<td>{{ $item->univ ?? '-' }}</td>
										<td>{{ $item->negara_asal_univ ?? '-' }}</td>
										<td>{{ $item->photo_url ?? '-' }}</td>
										<td>{{ $item->passport_url ?? '-' }}</td>
										<td>{{ $item->student_id_url ?? '-' }}</td>
										<td>{{ $item->loa_url ?? '-' }}</td>
										<td>{{ $item->cv_url ?? '-' }}</td>
										<td>{{ $item->pengajuan_dana_status ?? '-' }}</td>
										<td><form action="" method="GET">
												<button type="submit" class="btn btn-primary edit-button">Approve</button>
											</form>
										</td>
									</tr>
									@endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
                                        <th>No</th>
	                                    <th>Nama</th>
	                                    <th>Fakultas</th>
	                                    <th>Program</th>
	                                    <th>Part/Full Time</th>
                                        <th>Univ</th>
	                                    <th>Negara Asal</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
                                        <th>Status</th>
	                                    <th>Approve</th>
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