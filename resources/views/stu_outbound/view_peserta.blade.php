@extends('layouts.master') 

@section('content') 
    <h2>Student Outbound</h2>
    <p>This is View Peserta.</p>
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
										<th>NIM</th>
	                                    <th>Nama</th>
	                                    <th>Unit Kerja</th>
	                                    <th>Prodi</th>
	                                    <th>Program</th>
	                                    <th>Jenis Kelamin</th>
                                        <th>Via</th>
	                                    <th>Prodi Tujuan</th>
										<th>Fakultas Tujuan</th>
	                                    <th>Univ Tujuan</th>
										<th>Negara Tujuan</th>
	                                    <th>Jenjang</th>
                                        <th>Tgl. Mulai</th>
                                        <th>Tgl. Selesai</th>
	                                    <th>Full/Part Time</th>
	                                    <th>Created Date</th>
										<th>Foto</th>
										<th>Passport</th>
	                                    <th>CV</th>
	                                    <th>LoA</th>
                                        <th>Edit</th>
										<th>Delete</th>
	                                </tr>
	                            </thead>
	                            <tbody>
									@foreach ($data as $item)
									<tr>
										<td>{{ $item->nim ?? '-' }}</td>
										<td>{{ $item->nama_mhs ?? '-' }}</td>
										<td>{{ $item->unit_kerja_text ?? '-' }}</td>
										<td>{{ $item->prodi_text ?? '-' }}</td>
										<td>{{ $item->nama_program ?? '-' }}</td>
										<td>{{ $item->jk ?? '-' }}</td>
										<td>{{ $item->via ?? '-' }}</td>
										<td>{{ $item->prodi_tujuan_text ?? '-' }}</td>
										<td>{{ $item->fakultas_tujuan_text ?? '-' }}</td>
										<td>{{ $item->univ_tujuan_text ?? '-' }}</td>
										<td>{{ $item->negara_tujuan_text ?? '-' }}</td>
										<td>{{ $item->jenjang ?? '-' }}</td>
										<td>{{ $item->tgl_mulai ?? '-' }}</td>
										<td>{{ $item->tgl_selesai ?? '-' }}</td>
										<td>{{ $item->tipe_text ?? '-' }}</td>
										<td>{{ $item->created_date ?? '-' }}</td>
										<td>{{ $item->photo_url ?? '-' }}</td>
										<td>{{ $item->passport ?? '-' }}</td>
										<td>{{ $item->cv_url ?? '-' }}</td>
										<td>{{ $item->loa?? '-' }}</td>
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
										<th>NIM</th>
	                                    <th>Nama</th>
	                                    <th>Unit Kerja</th>
	                                    <th>Prodi</th>
	                                    <th>Program</th>
	                                    <th>Jenis Kelamin</th>
                                        <th>Via</th>
	                                    <th>Prodi Tujuan</th>
										<th>Fakultas Tujuan</th>
	                                    <th>Univ Tujuan</th>
										<th>Negara Tujuan</th>
	                                    <th>Jenjang</th>
                                        <th>Tgl. Mulai</th>
                                        <th>Tgl. Selesai</th>
	                                    <th>Full/Part Time</th>
	                                    <th>Created Date</th>
										<th>Foto</th>
										<th>Passport</th>
	                                    <th>CV</th>
	                                    <th>LoA</th>
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