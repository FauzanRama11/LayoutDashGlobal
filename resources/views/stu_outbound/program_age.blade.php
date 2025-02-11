@extends('layouts.master') 

@section('content') 

    <h2>Student Outbound</h2>
    <p>This is the Program AGE.</p>
    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="API-2">
								<a href= "{{route('prog_age.create')}}"><button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">+ Tambah</button></a>
	                            <thead>
	                                <tr>
                                        <th>Name</th>
	                                    <th>Started Date</th>
	                                    <th>End Date</th>
	                                    <th>Category</th>
	                                    <th>Via</th>
                                        <th>Kategori MBKM</th>
                                        <th>Host Unit</th>
	                                    <th>Universitas Tujuan</th>
	                                    <th>Negara Tujuan</th>
	                                    <th>Part/Full Time</th>
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
										<td>{{ $item->sub_mbkm ?? '-' }}</td>
										<td>{{ $item->unit ?? '-' }}</td>
										<td>{{ $item->universitas_tujuan ?? '-' }}</td>
										<td>{{ $item->negara_tujuan ?? '-' }}</td>
										<td>{{ $item->pt_ft ?? '-' }}</td>                                    
										<td>{{ $item->is_private_event === 'Ya' ? 'Pelaporan' : 'Registrasi' }}</td>
                                        <td>{{ $item->created_time ?? '-' }}</td>
                                        <td><form  action="{{ route('program_stuout.edit', ['id' => $item->id]) }}" method="GET">
												<button type="submit" class="btn btn-success edit-button">Edit</button>
											</form>
										</td>
										<td><form action="{{ route('prog_stuout.destroy', ['id' => $item-> id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dipulihkan')">
										@csrf
										@method('DELETE')		
										<button type="submit" class="btn btn-danger delete-button">Delete</button>
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
                                        <th>Kategori MBKM</th>
                                        <th>Host Unit</th>
	                                    <th>Universitas Tujuan</th>
	                                    <th>Negara Tujuan</th>
	                                    <th>Part/Full Time</th>
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