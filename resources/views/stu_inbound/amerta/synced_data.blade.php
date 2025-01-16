@extends('layouts.master') 

@section('title')Synced data | Amerta
@endsection

@section('content') 

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css')}}">
@endpush

    <h2>Student Inbound | Amerta</h2>
    <p>This is the Synced Data.</p>
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
	                                    <th>Nama</th>
	                                    <th>Surel</th>
	                                    <th>NIM</th>
	                                    <th>Kode MK</th>
	                                    <th>NM Fakultas</th>
                                        <th>NM Program Studi</th>
	                                    <th>NM Jenjang</th>
	                                    <th>Semester</th>
	                                </tr>
	                            </thead>
	                            <tbody>
									@foreach ($data as $item)
									<tr>
										<td>{{ $item->nama ?? '-' }}</td>
										<td>{{ $item->surel ?? '-' }}</td>
										<td>{{ $item->nim ?? '-' }}</td>
										<td>{{ $item->kode_mk ?? '-' }}</td>
										<td>{{ $item->nm_fakultas ?? '-' }}</td>
										<td>{{ $item->nm_proram_studi ?? '-' }}</td>      
										<td>{{ $item->nm_jenjang ?? '-' }}</td>
										<td>{{ $item->semester ?? '-' }}</td>    
									</tr>
									@endforeach
	                            </tbody>
	                            <tfoot>
	                                <tr>
                                        <th>Nama</th>
	                                    <th>Surel</th>
	                                    <th>NIM</th>
	                                    <th>Kode MK</th>
	                                    <th>NM Fakultas</th>
                                        <th>NM Program Studi</th>
	                                    <th>NM Jenjang</th>
	                                    <th>Semester</th>
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