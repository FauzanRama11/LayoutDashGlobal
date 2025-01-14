@extends('layouts.master') 

@section('content') 
    <h2>Database Agreement</h2>
    <p>This is the Database Agreement.</p>

    <div class="container-fluid">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="API-2">
                            @role("gpc")
								<a href= "form-pelaporan"><button class="btn btn-success btn-sm active" type="button"  style="width: 20%; margin:15px">+ Tambah</button></a>
	                        @endrole   
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Urut (Tahun)</th>
                                    <th>Country</th>
                                    <th>University / Institutions</th>
                                    <th>Type of Document</th>
                                    <th>Title of Agreement</th>
                                    <th>Link Download Naskah</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Active / Expired Per 12/01/2025</th>
                                    <th>Status Lapkerma</th>
                                    <th>Category of Document</th>
                                    <th>Skema</th>
                                    <th>Link Partnership Profile</th>
                                    <th>Year</th>
                                    <th>AGE Archive Serial Number</th>
                                    <th>Lapkerma Serial Number</th>
                                    <th>Website Lapkerma</th>
                                    <th>Faculty / Center / Unit (UNAIR)</th>
                                    <th>Department (UNAIR)</th>
                                    <th>Program Studi (UNAIR)</th>
                                    <th>Faculty / Center / Unit (Partners)</th>
                                    <th>Department (Partners)</th>
                                    <th>Program Studi (Partners)</th>
                                    <th>Type of Institution (Partners)</th>
                                    <th>QS WUR 2023</th>
                                    <th>QS WUR 100 by Subject 2022</th>
                                    <th>THE WUR 2022</th>
                                    <th>THE Impact Ranking 2021</th>
                                    <th>Region</th>
                                    <th>Type of Grant</th>
                                    <th>Source of Funding</th>
                                    <th>Sum of Funding</th>
                                    <th>Nama Penandatangan</th>
                                    <th>Designation (Jabatan)</th>
                                    <th>Nama Penandatangan Mitra</th>
                                    <th>Designation of Partner Signatory</th>
                                    <th>Collaboration Scope</th>
                                    <th>Name of Partner PIC</th>
                                    <th>Position of Partner PIC</th>
                                    <th>Email of Partner PIC</th>
                                    <th>Telephone / WA of Partner PIC</th>
                                    <th>Name of UNAIR PIC</th>
                                    <th>Position of UNAIR PIC</th>
                                    <th>Email of UNAIR PIC</th>
                                    <th>Telephone / WA of UNAIR PIC</th>
                                    <th>Involved Faculty at UNAIR</th>
                                    <th>World Rank (1-4)</th>
                                    <th>Number of Agreements (1-4)</th>
                                    <th>Level of Activities (1-4)</th>
                                    <th>Number of Faculty Involved (1-4)</th>
                                    <th>Scival Publication (1-4)</th>
                                    <th>Total Score</th>
                                    <th>Partnership Badge</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($merged as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->year_order_no }}</td>
                                        <td>{{ $item->country }}</td>
                                        <td>{{ $item->partner_involved}}</td> <!-- Update for the 'University / Institutions' -->
                                        <td>{{ $item->jenis_naskah }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td><a href="{{ $item->link_download_naskah }}" target="_blank">Download</a></td> <!-- Assuming it's a link -->
                                        <td>{{ $item->mou_start_date }}</td>
                                        <td>{{ $item->mou_end_date }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->mou_end_date)->isPast() ? 'Expired' : 'Active' }}</td> <!-- Active/Expired logic -->
                                        <td>{{ $item->status_lapkerma }}</td>
                                        <td>{{ $item->category_document }}</td>
                                        <td>{{ $item->skema }}</td>
                                        <td><a href="{{ $item->link_partnership_profile }}" target="_blank">View Profile</a></td>
                                        <td>{{ $item->year }}</td>
                                        <td>{{ $item->age_archive_sn }}</td>
                                        <td>{{ $item->lapkerma_archive }}</td>
                                        <td><a href="{{ $item->website_lapkerma }}" target="_blank">Visit Website</a></td>
                                        <td>{{ $item->fakultas }}</td>
                                        <td>{{ $item->department_unair }}</td>
                                        <td>{{ $item->prodi_involved }}</td>
                                        <td>{{ $item->faculty_partner }}</td>
                                        <td>{{ $item->department_partner }}</td>
                                        <td>{{ $item->program_study_partner }}</td>
                                        <td>{{ $item->type_institution_partner }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $item->region }}</td>
                                        <td>{{ $item->type_grant }}</td>
                                        <td>{{ $item->source_funding }}</td>
                                        <td>{{ $item->sum_funding }}</td>
                                        <td>{{ $item->signatories_unair_name }}</td>
                                        <td>{{ $item->signatories_unair_pos }}</td>
                                        <td>{{ $item->signatories_partner_name }}</td>
                                        <td>{{ $item->signatories_partner_pos }}</td>
                                        <td>{{ $item->collaboration_scope }}</td>
                                        <td>{{ $item->pic_mitra_nama }}</td>
                                        <td>{{ $item->pic_mitra_jabatan }}</td>
                                        <td>{{ $item->pic_mitra_email }}</td>
                                        <td>{{ $item->pic_mitra_phone }}</td>
                                        <td>{{ $item->pic_fak_nama }}</td>
                                        <td>{{ $item->pic_fak_jabatan }}</td>
                                        <td>{{ $item->pic_fak_email }}</td>
                                        <td>{{ $item->pic_fak_phone }}</td>
                                        <td>{{ $item->faculty_involved }}</td>
                                        <td>{{ $item->world_rank }}</td>
                                        <td>{{ $item->number_agreements }}</td>
                                        <td>{{ $item->level_activities }}</td>
                                        <td>{{ $item->number_faculty_involved }}</td>
                                        <td>{{ $item->scival_publication }}</td>
                                        <td>{{ $item->total_score }}</td>
                                        <td>{{ $item->partnership_badge }}</td>
                                        <td></td>
                                        <td><form  action="{{ route('database_agreement.destroy', ['id' => $item-> id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dipulihkan')">
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
                                        <th>No</th>
                                        <th>No Urut (Tahun)</th>
                                        <th>Country</th>
                                        <th>University / Institutions</th>
                                        <th>Type of Document</th>
                                        <th>Title of Agreement</th>
                                        <th>Link Download Naskah</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Active / Expired Per 12/01/2025</th>
                                        <th>Status Lapkerma</th>
                                        <th>Category of Document</th>
                                        <th>Skema</th>
                                        <th>Link Partnership Profile</th>
                                        <th>Year</th>
                                        <th>AGE Archive Serial Number</th>
                                        <th>Lapkerma Serial Number</th>
                                        <th>Website Lapkerma</th>
                                        <th>Faculty / Center / Unit (UNAIR)</th>
                                        <th>Department (UNAIR)</th>
                                        <th>Program Studi (UNAIR)</th>
                                        <th>Faculty / Center / Unit (Partners)</th>
                                        <th>Department (Partners)</th>
                                        <th>Program Studi (Partners)</th>
                                        <th>Type of Institution (Partners)</th>
                                        <th>QS WUR 2023</th>
                                        <th>QS WUR 100 by Subject 2022</th>
                                        <th>THE WUR 2022</th>
                                        <th>THE Impact Ranking 2021</th>
                                        <th>Region</th>
                                        <th>Type of Grant</th>
                                        <th>Source of Funding</th>
                                        <th>Sum of Funding</th>
                                        <th>Nama Penandatangan</th>
                                        <th>Designation (Jabatan)</th>
                                        <th>Nama Penandatangan Mitra</th>
                                        <th>Designation of Partner Signatory</th>
                                        <th>Collaboration Scope</th>
                                        <th>Name of Partner PIC</th>
                                        <th>Position of Partner PIC</th>
                                        <th>Email of Partner PIC</th>
                                        <th>Telephone / WA of Partner PIC</th>
                                        <th>Name of UNAIR PIC</th>
                                        <th>Position of UNAIR PIC</th>
                                        <th>Email of UNAIR PIC</th>
                                        <th>Telephone / WA of UNAIR PIC</th>
                                        <th>Involved Faculty at UNAIR</th>
                                        <th>World Rank (1-4)</th>
                                        <th>Number of Agreements (1-4)</th>
                                        <th>Level of Activities (1-4)</th>
                                        <th>Number of Faculty Involved (1-4)</th>
                                        <th>Scival Publication (1-4)</th>
                                        <th>Total Score</th>
                                        <th>Partnership Badge</th>
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