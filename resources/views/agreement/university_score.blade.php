@extends('layouts.master') 

@section('content') 
    <h2>University Score</h2>
    <p>This is University Score.</p>

    
    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
							
	                        <table class="display" id="norm-2" data-columns-export=":not(:eq(0))">
							@role("fakultas")
								<a href= ""><button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">+ Tambah</button></a>
	                        @endrole    
							<thead>
	                                <tr>
                                        <th>No</th>
	                                    <th>University</th>
	                                    <th>MoU</th>
	                                    <th>MoA</th>
	                                    <th>IA</th>
                                        <th>Score Agreement</th>
                                        <th>Student Inbound</th>
	                                    <th>Student Outbound</th>
	                                    <th>Staff Inbound</th>
	                                    <th>Staff Outbound</th>
                                        <th>Total</th>
	                                    <th>Status</th>

	                                </tr>
	                            </thead>
	                            <tbody>
                                @foreach ($query as $item )
									<tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$item->university_name}}</td>
                                            <td>{{$item->total_mou}}</td>
                                            <td>{{$item->total_moa}}</td>
                                            <td>{{$item->total_ia}}</td>
                                            <td>{{$item->score}}</td>
                                            <td>{{$item->student_inbound}}</td>
                                            <td>{{$item->student_outbound}}</td>
                                            <td>{{$item->staff_inbound}}</td>
                                            <td>{{$item->staff_outbound}}</td>                                    
                                            <td>{{$item->kumulatif_score}}</td>
                                            <td>
												@if ($item->status == "active")
													<button class="btn btn-success btn-sm">ACTIVE</button>
												@else
													<button class="btn btn-danger btn-sm">SLEEPING</button>
												@endif
										</td>
									</tr>
                                    @endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
                                        <th>No</th>
	                                    <th>University</th>
	                                    <th>MoU</th>
	                                    <th>MoA</th>
	                                    <th>IA</th>
                                        <th>Score Agreement</th>
                                        <th>Student Inbound</th>
	                                    <th>Student Outbound</th>
	                                    <th>Staff Inbound</th>
	                                    <th>Staff Outbound</th>
                                        <th>Total</th>
	                                    <th>Status</th>
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