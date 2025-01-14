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
	                        <table class="display" id="API-2">
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
										<td>{{$item -> mou_end_date}}</td>
										<td>{{$item -> created_date}}</td>
										<td><form action="{{ route('pelaporan.edit', ['id' => $item->id]) }}" method="GET">
                                                <button class="btn btn-success btn-sm" 
                                                        type="submit" 
                                                        data-toggle="tooltip" 
                                                        data-placement="top" 
                                                        title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </form>
                                        </td>
										<td>  <form  action="{{ route('pelaporan.destroy', ['id' => $item-> id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dipulihkan')">
												@csrf
												@method('DELETE')
												<button class="btn btn-danger btn-sm mr-2" type="submit" data-toggle="tooltip" data-placement="top" title="Delete">
													<i class="fa fa-trash"></i>
												</button>
											</form></td>    
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