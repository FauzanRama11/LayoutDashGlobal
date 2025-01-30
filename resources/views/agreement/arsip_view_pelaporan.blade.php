@extends('layouts.master') 

@section('content') 
    <h2>Pelaporan</h2>
    <p>This is Pelaporan.</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="norm-1" data-columns-export=":not(:eq(0)):not(:gt(7))">
                            @role("wadek3")
								<a href= "form-pelaporan"><button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">+ Tambah</button></a>
	                        @endrole   
	                            <thead>
	                                <tr>
	                                    <th>No.</th>
	                                    <th>Tittle of Agreement</th>
	                                    <th>University/Institutions</th>
	                                    <th>Fakultas Pengaju</th>
	                                    <th>Type of Document</th>
	                                    <th>Approval</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Created Date</th>
                                        <th>See</th>
										<th>Delete</th>
	                                </tr>
	                            </thead>
	                            <tbody>
                                @foreach ( $data as $item )
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{$item -> title}}</td>
										<td>{{$item ->partner}}</td>
										<td>{{$item -> fakultas}}</td>
										<td>{{$item -> jenis_naskah}}</td>                                
										<td class = "col-53" style="width: 100px;">
                                            @if ($item->approval_pelaporan == 1)
                                            	<button type="submit" class="btn btn-success btn-sm">APPROVED</button>
											@elseif ($item->approval_pelaporan == 0 && $item->approval_status == null)
                                            	<button type="submit" class="btn btn-dark btn-sm">SUBMITTED</button>
                                            @elseif ($item->approval_status == 'NEED REVISE')
                                            	<button type="submit" class="btn btn-warning btn-sm">NEED REVISE</button>
                                            @elseif ($item->approval_status == 'REJECTED')
                                            	<button type="submit" class="btn btn-danger btn-sm">REJECTED</button>
                                            @endif
                                        </td>
										<td>{{$item -> mou_start_date}}</td>
										<td>
											@if ($item->mou_end_date != "0000-00-00")
												{{$item->mou_end_date}}
											@else
												{{ $item->text_end_date }}
											@endif
										</td>
										<td>{{$item ->created_date}}</td>
										<td>
											<form action="{{ route('pelaporan.edit', ['id' => $item->id]) }}" method="GET">
                                                <button class="btn btn-success btn-sm" 
                                                        type="submit" 
                                                        data-toggle="tooltip" 
                                                        data-placement="top" 
                                                        title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </form>
                                        </td>
										<td>  
											<form id="deleteForm{{ $item->id }}" action="{{ route('pelaporan.destroy', ['id' => $item->id]) }}" method="POST">
												@csrf
												@method('DELETE')
												<button type="button" class="btn btn-danger btn-sm mr-2" onclick="confirmDelete('{{ $item->id }}', 'Data yang telah dihapus tidak dapat dipulihkan')">
													<i class="fa fa-trash"></i>
												</button>
											</form>
										</td>       
									</tr>
                                @endforeach
	                            </tbody>
	                            <tfoot>
	                            <tr>
	                                <th>No.</th>
	                                <th>Tittle of Agreement</th>
	                                <th>University/Institutions</th>
	                                <th>Fakultas Pengaju</th>
	                                <th>Type of Document</th>
	                                <th>Approval</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Created Date</th>
                                    <th>See</th>
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


<!-- SweetAlert2  -->
<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
function confirmDelete(itemId, message) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus data!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`deleteForm${itemId}`);
            if (form) {
                $.ajax({
                    url: form.action, // URL dari atribut action form
                    type: 'POST', // Laravel DELETE menggunakan POST + _method
                    data: $(form).serialize(), // Kirim CSRF token dan _method
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Refresh halaman
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message || 'Tidak dapat menghapus data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText); // Debugging
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Form penghapusan tidak ditemukan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}



</script>