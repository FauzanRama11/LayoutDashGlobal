@extends('layouts.master') 

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
<!-- 
    <h5>Your Program</h5>
    <span>This is Program.</span> -->
<div class="product-wrapper-grid">
	<div class="row">
		@if($programs)
		@foreach ($programs as $data)
			@php
				$modalId = 'exampleModalCenter' . $loop->index; // ID unik untuk modal
			@endphp
			<div class="col-xl-3 col-sm-6 xl-4">
								<div class="card">
									<div class="product-box">
										<div class="product-img">
											@if($data->is_approved == -1)
												<div class="ribbon ribbon-danger">Rejected</div>
											@elseif($data->is_approved == 1)
												<div class="ribbon ribbon-success">Approved</div>
											@elseif($data->is_approved == 0)
												<div class="ribbon ribbon-warning">Hasn't Processed</div>
											@endif

												@if($data->logo_base64)
													<img class="img-fluid" src="{{$data->logo_base64}}" alt="" />
												@else
													<img class="img-fluid" src="{{asset('assets/images/ecommerce/02.jpg')}}" alt="" />
												@endif
											<div class="product-hover">
												<ul>
													<li>
													<a data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
														<i class="icon-eye"></i>
													</a>
													</li>
												</ul>
											</div>
										</div>
										<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">

											<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<div class="product-box row">
															@if($data->logo_base64)
																<div class="product-img col-lg-6"><img class="img-fluid " src="{{$data->logo_base64}}" alt="" /></div>
															@else
																<div class="product-img col-lg-6"><img class="img-fluid " src="{{asset('assets/images/ecommerce/02.jpg')}}" alt="" /></div>
															
															@endif
															
															<div class="product-details col-lg-6 text-start">
																<a href="product-page"> <h4>{{$data->nama_program}}</h4></a>
																<table>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>Start Date</b></td>
																			<td><span style="color: red; padding-left: 15px;">{{$data->start_date}}</span></td>
																		</div>
																	</tr>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>End Date</b></td>
																			<td><span style="color: red; padding-left: 15px;">{{$data->end_date}}</span></td>
																		</div>
																	</tr>
						
																</table>
																<br>
																
																<div class="product-view">
																	<h6 class="f-w-600">Program Details</h6>
																	<p class="mb-0">{{$data->description}}</p>

																	<table>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>Host Unit</b></td>
																			<td><span style="color: red;">{{$data->host_unit_text}}</span></td>
																		</div>
																	</tr>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>Program Category</b></td>
																			<td><span style="color: red;">{{$data->category_text}}</span></td>
																		</div>
																	</tr>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>PIC</b></td>
																			<td><span style="color: red;">{{$data->pic}}</span></td>
																		</div>
																	</tr>
																</table>

																</div>
																<div class="product-size">
																<div class="addcart-btn">
																		<a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#productModal{{ $loop->index }}">
																			Your Details
																		</a>
																	</div>

																</div>
																<div class="product-qnty">
																
																</div>
															</div>
														</div>
														<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
												</div>
											</div>
										</div>
										<div class="modal fade" id="productModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="productModalLabel{{ $loop->index }}" aria-hidden="true">
											<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<div class="product-box row">
															@if($data->photo_base64)
																<div class="product-img col-lg-6"><img class="img-fluid" src="{{$data->photo_base64}}" alt="" /></div>
															@else
																<div class="product-img col-lg-6"><img class="img-fluid" src="{{asset('assets/images/ecommerce/02.jpg')}}" alt="" /></div>
															@endif
															
															<div class="product-details col-lg-6 text-start">
															
																<a href="product-page"> <h4>{{$data->nama}}</h4></a>
															
																<table>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>Registration</b></td>
																			<td><span style="color: red;">{{$data->reg_time}}</span></td>
																		</div>
																	</tr>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>Degree</b></td>
																			<td><span style="color: red;">{{$data->jenjang}}</span></td>
																		</div>
																	</tr>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>Home Faculty</b></td>
																			<td><span style="color: red;">{{$data->fakultas_asal}}</span></td>
																		</div>
																	</tr>
																	<tr>
																		<div class="product-price">
																			<td style="color:#24695c"><b>Home Study Program</b></td>
																			<td><span style="color: red;">{{$data->prodi_asal}}</span></td>
																		</div>
																	</tr>
																</table>
																<br>
																
																<div class="product-view">
																<a href="product-page"><h4>Progress</h4></a>
																	<br>
																	<table>
																		<tr>
																			<td style="color:#24695c"><b>Approval</b></td>
																			<td style="padding-left: 15px;">
																				@if ($data->is_approved === 1)
																					<button class="btn btn-success btn-sm" disabled>Approved</button>
																				@elseif ($data->is_approved === -1)
																					<button class="btn btn-danger btn-sm" disabled>Rejected</button>
																					@if (!empty($data->revision_note))
																						<button type="button" class="btn btn-warning btn-sm viewRevisionButton" data-revision="{{ $data->revision_note }}">
																							<i class="fa fa-eye"></i> 
																						</button>
																					@endif
																				@else
																					<button class="btn btn-info btn-sm" disabled>Processed</button>
																					@if (!empty($data->revision_note))
																						<button type="button" class="btn btn-warning btn-sm viewRevisionButton" data-revision="{{ $data->revision_note }}">
																							<i class="fa fa-eye"></i> 
																						</button>
																					@endif
																				@endif
																			</td>
																		</tr>
																		<tr>
																			<td style="color:#24695c"><b>LoA</b></td>
																			<td  style="padding-left: 15px;">
																				@if ($url = getFileUrl($data->loa_url))
																					<a href="{{ $url }}" target="_blank" class="btn btn-primary">
																						<i class="fa fa-download"></i>
																					</a>
																				@endif
																			</td>
																		</tr>
																		<tr>
																			<td style="color:#24695c"><b>Allowance</b></td>
																			<td  style="padding-left: 15px;">
																				@if ($data->pengajuan_dana_status === 'APPROVED')
																					<button class="btn btn-success btn-sm" disabled>Approved  [ {{ $data->sumber_dana ?? ''}}]</button>
																				@elseif ($data->pengajuan_dana_status === 'REQUESTED')
																					<button class="btn btn-warning btn-sm" disabled>Requesting</button>
																				@else
																					<button class="btn btn-info btn-sm" disabled>Not Requesting</button>
																				@endif
																			</td>
																		</tr>
																	</table>
																</div>
																
																<div class="product-size">
																	<div class="addcart-btn">
																		<a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#productModal{{ $loop->index }}">
																			Program Info
																		</a>
																	</div>
																</div>
															</div>
														</div>
														<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
												</div>
											</div>
										</div>


										<div class="product-details">
											<a href="product-page"> <h4>{{$data->nama_program}}</h4></a>
											<p>{{$data->category_text}}</p>
											<div class="product-price">
												Start Date
												<span style="color: red; margin-left: 15px;">{{$data->start_date}}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
			@endforeach
		@endif
	</div>
</div>
<script src="{{ asset('assets/js/datatable/datatables/jquery-3.6.0.min.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $(document).on("click", ".viewRevisionButton", function () {
        let revisionNote = $(this).data("revision");
        console.log("Button clicked, revision note:", revisionNote); // Debugging

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
@endsection



