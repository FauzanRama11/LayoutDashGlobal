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
	                                    <th>Reject</th>
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
												@if (!empty($item->revision_note)) 
													<!-- Tombol untuk melihat revisi -->
													<button type="button" class="btn btn-warning btn-sm viewRevisionButton" 
														data-revision="{{ $item->revision_note }}">
														<i class="fa fa-eye"></i> 
													</button>
												@endif
											@else
												<button class="btn btn-info btn-sm" disabled>Processed</button>
												@if (!empty($item->revision_note)) 
													<!-- Tombol untuk melihat revisi -->
													<button type="button" class="btn btn-warning btn-sm viewRevisionButton" 
														data-revision="{{ $item->revision_note }}">
														<i class="fa fa-eye"></i> 
													</button>
												@endif
											@endif
										</td>
										<td>
											@if ($item->is_approved === 1)
												<form action="{{ route('stuin.unapprove', $item->id) }}" method="POST">
													@csrf
													<button type="submit" class="btn btn-primary edit-button">Unapprove</button>
												</form>
											@else
												<form action="{{ route('stuin.approve', $item->id) }}" method="POST">
													@csrf
													<button type="submit" class="btn btn-primary edit-button">Approve</button>
												</form>
											@endif
										</td>
										<td>
											<button type="button" class="btn btn-warning reviseButton" data-id="{{ $item->id }}">
												<i class="fa fa-edit"></i> Revise
											</button>
										</td>
										<td>
											<form action="{{ route('stuin.reject', $item->id) }}" method="POST">
												@csrf
												<button type="submit" class="btn btn-danger edit-button">Reject</button>
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
	                                    <th>Reject</th>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
	$(document).ready(function () {
		$(document).on("click", ".reviseButton", function () {
			let id = $(this).data("id");
			console.log("ID yang dikirim:", id); 

			Swal.fire({
				title: 'Revisi Data',
				input: 'text',
				inputPlaceholder: 'Masukkan catatan revisi di sini...',
				showCancelButton: true,
				confirmButtonText: '<i class="fa fa-save"></i> Simpan',
				cancelButtonText: '<i class="fa fa-times"></i> Batal',
				confirmButtonColor: "#007bff",
				cancelButtonColor: "#d33",
				inputValidator: (value) => {
					if (!value.trim()) {
						return 'Catatan revisi tidak boleh kosong!';
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					let revisionNote = result.value;
					console.log("Revisi Note:", revisionNote); 
	
					Swal.fire({
						title: 'Menyimpan...',
						text: 'Mohon tunggu sementara revisi disimpan.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
	
					$.ajax({
						url: "/student-inbound/save-revision/" + id, 
						type: "POST",
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
						},
						data: {
							revision_note: revisionNote
						},
						success: function (response) {
							console.log("Response sukses:", response); 
							Swal.fire('Berhasil!', 'Revisi berhasil disimpan.', 'success')
							.then(() => {
								location.reload();
							});
						},
						error: function (xhr) {
							console.log("Error response:", xhr.responseText); 
							Swal.fire('Error!', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
						}
					});
				}
			});
		});
	
		$(document).on("click", ".viewRevisionButton", function () {
			let revisionNote = $(this).data("revision"); 
			Swal.fire({
				title: 'Revisi',
				text: revisionNote,
				icon: 'info',
				confirmButtonText: 'Tutup',
				confirmButtonColor: "#007bff"
			});
		});
	});
	</script>
	