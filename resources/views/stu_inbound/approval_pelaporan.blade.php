@extends('layouts.master') 

@section('title')Approval Pelaporan | Student Inbound
@endsection

@section('content') 

@php										
function getFileUrl($fileUrl) {
												
	if (empty($fileUrl)) return null;

		$filePath = ltrim(str_replace('repo/', '', $fileUrl), '/');
		$segments = explode('/', $filePath);
		$fileName = array_pop($segments);
		$folder = implode('/', $segments);

	return !empty($folder)
		? route('view.dokumen', ['folder' => $folder, 'fileName' => $fileName]) 
		: route('view.dokumen', ['folder' => $fileName]);	
}

@endphp


    <h2>Student Inbound</h2>
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
                                        <td>{{ $item->id ?? '' }}</td>
										<td>{{ $item->nama ?? '' }}</td>
										<td>{{ $item->host_unit ?? '' }}</td>
										<td>{{ $item->program ?? '' }}</td>
										<td>{{ $item->tipe ?? '' }}</td>
										<td>{{ $item->univ_name ?? '' }}</td>
										<td>{{ $item->start_date ?? '' }}</td>
										<td>{{ $item->end_date ?? '' }}</td>
										<td>{{ $item->via ?? '' }}</td>
										<td>{{ $item->country_name ?? '' }}</td>

										
								
										<td>
											@if ($url = getFileUrl($item->photo_url))
												<a href="{{ $url }}" target="_blank" class="btn btn-primary">
													<i class="fa fa-download"></i> 
												</a>
											@endif
										</td>
								
										<td>
											@if ($url = getFileUrl($item->passport_url))
												<a href="{{ $url }}" target="_blank" class="btn btn-primary">
													<i class="fa fa-download"></i> 
												</a>
											@endif
										</td>
								
										<td>
											@if ($url = getFileUrl($item->student_id_url))
												<a href="{{ $url }}" target="_blank" class="btn btn-primary">
													<i class="fa fa-download"></i> 
												</a>
											@endif
										</td>
								
										<td>
											@if ($url = getFileUrl($item->loa_url))
												<a href="{{ $url }}" target="_blank" class="btn btn-primary">
													<i class="fa fa-download"></i>
												</a>
											@endif
										</td>

										<td>
											@if ($url = getFileUrl($item->cv_url))
												<a href="{{ $url }}" target="_blank" class="btn btn-primary">
													<i class="fa fa-download"></i>
												</a>
											@endif
										</td>
										
										<td>
											@if ($item->is_approved === 1)
												<button class="btn btn-success btn-sm" disabled>Approved</button>
											@elseif ($item->is_approved === -1)
												<button class="btn btn-danger btn-sm" disabled>Rejected</button>
											@else
												<button class="btn btn-info btn-sm" disabled>Processed</button>
											@endif
										</td>
										<td>
											<form action="{{ route('stuin.approve', $item->id) }}" method="POST">
												@csrf
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