@extends('layouts.master') 

@section('content') 
    <h2>Student Outbound</h2>
    <p>This is Approval Pelaporan.</p>

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
	                                    <th>Tanggal Mulai</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Via</th>
                                        <th>Country</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
                                        <th>Status</th>
										<th>Approve</th>
	                                    <th>Add Revision</th>
	                                </tr>
	                            </thead>
	                            <tbody>
                                @foreach ($data as $item)
									<tr>
                                        <td>{{ $item->id ?? '-' }}</td>
										<td>{{ $item->nama ?? '-' }}</td>
										<td>{{ $item->host_unit ?? '-' }}</td>
										<td>{{ $item->program ?? '-' }}</td>
										<td>{{ $item->tipe ?? '-' }}</td>
										<td>{{ $item->univ_name ?? '-' }}</td>
										<td>{{ $item->start_date ?? '-' }}</td>
										<td>{{ $item->end_date ?? '-' }}</td>
										<td>{{ $item->via ?? '-' }}</td>
										<td>{{ $item->country_name ?? '-' }}</td>
										<td>{{ $item->photo_url ?? '-' }}</td>
										<td>{{ $item->passport_url ?? '-' }}</td>
										<td>{{ $item->student_id_url ?? '-' }}</td>
										<td>{{ $item->loa_url ?? '-' }}</td>
										<td>{{ $item->cv_url ?? '-' }}</td>
										<td>{{ $item->is_approve ?? '-' }}</td>
										<td><form action="" method="GET">
												<button type="submit" class="btn btn-primary edit-button">Approve</button>
											</form>
										</td>
										<td><form action="" method="GET">
												<button type="submit" class="btn btn-primary edit-button">Revise</button>
											</form>
										</td>
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
	                                    <th>Tanggal Mulai</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Via</th>
                                        <th>Country</th>
										<th>Photo</th>
	                                    <th>Passport</th>
										<th>Student ID</th>
	                                    <th>LoA</th>
                                        <th>CV</th>
                                        <th>Status</th>
										<th>Approve</th>
	                                    <th>Add Revision</th>
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