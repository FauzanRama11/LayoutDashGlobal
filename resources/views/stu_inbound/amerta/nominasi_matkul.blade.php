@extends('layouts.master') 

@section('title')Nominasi Mata Kuliah | Amerta
@endsection

@section('content') 
    
    <h2>Student Inbound | Amerta</h2>
    <p>This is the Nominasi Mata Kuliah</p>
    
    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="API-2">
                                <a href="{{ route('am_form_matkul.create') }}">
									<button class="btn btn-success btn-sm active" type="button" style="width: 20%; margin:15px">+ Tambah</button>
								</a>

	                            <thead>
                                    <tr>
                                        <th>Periode Program</th>
                                        <th>Prodi</th>
                                        <th>Fakultas</th>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Semester</th>
                                        <th>SKS</th>
                                        <th>Tgl Submit</th>
                                        <th>Status Matkul</th>
                                        <th>Edit</th>
										<th>Delete</th>
                                    </tr>
                                    </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->date_program ?? '-' }}</td>
                                        <td>{{ $item->name ?? '-' }}</td>
                                        <td>{{ $item->nama_ind ?? '-' }}</td>
                                        <td>{{ $item->code ?? '-' }}</td>
                                        <td>{{ $item->title ?? '-' }}</td>
                                        <td>{{ $item->semester ?? '-' }}</td>
                                        <td>{{ $item->sks ?? '-' }}</td>
                                        <td>{{ $item->created_date}}</td>
										<td>
											@if ($item->status === 'approved')
												<span class="badge badge-primary">Aktif</span>
											@else
												<span class="badge badge-info">Tidak Aktif</span>
											@endif
										</td>
                                        <td>
											<a href="{{ route('am_form_matkul.edit', ['id' => $item->id]) }}" class="btn btn-primary btn-sm">Edit</a>
										</td>
										<td>
											<form action="{{ route('am_hapus_matkul', ['id' => $item->id]) }}" method="POST">
												@csrf
												@method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
											</form>
										</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Periode Program</th>
                                        <th>Prodi</th>
                                        <th>Fakultas</th>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Semester</th>
                                        <th>SKS</th>
                                        <th>Tgl Submit</th>
                                        <th>Status Matkul</th>
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
