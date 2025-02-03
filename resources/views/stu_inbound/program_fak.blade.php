@extends('layouts.master') 



@section('content') 
    <h2>Student Inbound</h2>
    <p>This is the Program Fakultas.</p>
    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="API-2">
								@role("fakultas")
								<a href="{{ route('stuin_fak.create') }}">
									<button class="btn btn-success btn-sm active" type="button" style="width: 20%; margin:15px">+ Tambah</button>
								</a>
								@endrole    
	                            <thead>
	                                <tr>
	                                    <th>Name</th>
	                                    <th>Started Date</th>
	                                    <th>End Date</th>
	                                    <th>Category</th>
	                                    <th>Via</th>
                                        <th>Host Unit</th>
	                                    <th>Full/Part Time</th>
	                                    <th>Jumlah Pendatar</th>
	                                    <th>Jenis</th>
	                                    <th>Created Time</th>
	                                    <th>Edit</th>
                                        <th>Delete</th>
	                                </tr>
	                            </thead>
	                            <tbody>
									@foreach ($data as $item)
									<tr>
										<td>{{ $item->name ?? '-' }}</td>
										<td>{{ $item->start_date ?? '-' }}</td>
										<td>{{ $item->end_date ?? '-' }}</td>
										<td>{{ $item->cat ?? '-' }}</td>
										<td>{{ $item->via ?? '-' }}</td>
										<td>{{ $item->unit ?? '-' }}</td>
										<td>{{ $item->pt_ft ?? '-' }}</td>
										<td>{{ $item->peserta ?? '-' }}</td>
										<td>{{ $item->is_private_event === 'Ya' ? 'Registrasi' : 'Pelaporan' }}</td>
										<td>{{ $item->created_time ?? '-' }}</td>                                    
										<td>
											<form  action="{{ route('program_stuin.edit', ['id' => $item->id]) }}" method="GET">
												<button type="submit" class="btn btn-primary edit-button">Edit</button>
											</form>
										</td>
										<td>
											<form action="{{ route('program_stuin.destroy', ['id' => $item-> id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dipulihkan')">
												@csrf
												@method('DELETE')		
												<button type="submit" class="btn btn-primary delete-button">Delete</button>
											</form>
										</td>
									</tr>
									@endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
	                                    <th>Name</th>
	                                    <th>Started Date</th>
	                                    <th>End Date</th>
	                                    <th>Category</th>
	                                    <th>Via</th>
                                        <th>Host Unit</th>
	                                    <th>Full/Part Time</th>
	                                    <th>Jumlah Pendatar</th>
	                                    <th>Jenis</th>
	                                    <th>Created Time</th>
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