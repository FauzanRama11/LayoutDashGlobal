@extends('layouts.master') 

@section('content') 
    <h2>Review</h2>
    <p>This is Review.</p>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0 rounded">
                    <div class="card-body">
                        <!-- Button Group -->
                        <div class="btn-group d-flex flex-wrap mb-4" role="group" aria-label="Filter Buttons">
                            <a href="{{ route('review_agreement', ['current_role' => 'all']) }}" 
                               class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                               {{ request('current_role') == 'all' ? 'active' : '' }}">All</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'dosen']) }}" 
                               class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                               {{ request('current_role') == 'dosen' ? 'active' : '' }}">Dosen</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'wadek3']) }}" 
                               class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                               {{ request('current_role') == 'wadek3' ? 'active' : '' }}">Wadek3/Wadir/KU</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'age']) }}" 
                               class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                               {{ request('current_role') == 'age' ? 'active' : '' }}">AGE</a>

                               <a href="{{ route('review_agreement', ['current_role' => 'dipp']) }}" 
                            class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                            {{ request('current_role') == 'dipp' ? 'active' : '' }}">DIPP</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'bpbrin']) }}" 
                            class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                            {{ request('current_role') == 'bpbrin' ? 'active' : '' }}">BPBRIN</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'hukum']) }}" 
                            class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                            {{ request('current_role') == 'hukum' ? 'active' : '' }}">Hukum</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'sekretaris']) }}" 
                            class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                            {{ request('current_role') == 'sekretaris' ? 'active' : '' }}">SU</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'warek_ama']) }}" 
                            class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                            {{ request('current_role') == 'warek_ama' ? 'active' : '' }}">AMA</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'warek_idi']) }}" 
                            class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                            {{ request('current_role') == 'warek_idi' ? 'active' : '' }}">IDI</a>

                            <a href="{{ route('review_agreement', ['current_role' => 'ksln_ttd']) }}" 
                            class="btn btn-outline-primary btn-sm rounded-pill me-2 mb-2 
                            {{ request('current_role') == 'ksln_ttd' ? 'active' : '' }}">TTD</a>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="display" id="norm-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Applicant</th>
                                        <th>Application Date</th>
                                        <th>Faculty/Unit</th>
                                        <th>Type of Agreement</th>
                                        <th>Partners</th>
                                        <th>Title</th>
                                        <th>Days</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->applicant }}</td>
                                        <td>{{ $row->created_date }}</td>
                                        <td>{{ $row->faculty }}</td>
                                        <td>{{ $row->jenis_naskah }}</td>
                                        <td>{{ $row->partner }}</td>
                                        <td>{{ $row->title }}</td>
                                        <td style="width: 55px; text-align: center;">
                                            @if($row->days <= 3)
                                                <button class="btn btn-success btn-sm">
                                                    +{{ $row->days }}
                                                </button>
                                            @elseif($row->days > 3 && $row->days <= 6)
                                                <button class="btn btn-warning btn-sm">
                                                    +{{ $row->days }}
                                                </button>
                                            @else
                                                <button class="btn btn-danger btn-sm">
                                                    +{{ $row->days }}
                                                </button>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Applicant</th>
                                        <th>Application Date</th>
                                        <th>Faculty/Unit</th>
                                        <th>Type of Agreement</th>
                                        <th>Partners</th>
                                        <th>Title</th>
                                        <th>Days</th>
                                        <th>Edit</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection