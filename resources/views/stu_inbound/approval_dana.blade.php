@extends('layouts.master') 

@section('title')Approval Dana | Student Inbound
@endsection

@section('content') 

@php										
function getFileUrl($fileUrl) {
												
	if (empty($fileUrl)) return null;

		$filePath = ltrim(str_replace('repo/', '', $fileUrl), '/');
		$segments = explode('/', $filePath);
		$fileName = array_pop($segments);
		$folder = implode('/', $segments);

		$encodedFileName = urlencode($fileName);
		$encodedFolder = urlencode($folder);

	return !empty($folder)
		? route('view.dokumen', ['folder' => $encodedFolder, 'fileName' => $encodedFileName]) 
		: route('view.dokumen', ['folder' => $encodedFileName]);	
}

@endphp

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
										<td>{{ $item->id ?? '' }}</td>
										<td>{{ $item->nama ?? '' }}</td>
										<td>{{ $item->fakultas ?? '' }}</td>
										<td>{{ $item->program ?? '' }}</td>
										<td>{{ $item->tipe ?? '' }}</td>
										<td>{{ $item->univ ?? '' }}</td>
										<td>{{ $item->negara_asal_univ ?? '' }}</td>
										
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
											@if ($item->dana_status === 'APPROVED')
												<button class="btn btn-success btn-sm" disabled>Approved</button>
											@elseif ($item->dana_status === 'REQUESTED')
												<button class="btn btn-warning btn-sm" disabled>Requested</button>
											@endif
										</td>
										<td>
											<form action="{{ route('stuin.approve.dana', $item->id) }}" method="POST">
												@csrf
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