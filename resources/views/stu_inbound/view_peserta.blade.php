@extends('layouts.master')

@section('content')

<h2>Student Inbound</h2>
<p>This is View Peserta.</p>

<div class="container-fluid">
    <div class="row">
        <!-- Individual column searching (text inputs) Starts -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="API-2">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Unit Kerja</th>
                                    <th>Jenjang</th>
                                    <th>Prodi Asal</th>
                                    <th>Fakultas Asal</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Via</th>
                                    <th>Full/Part Time</th>
                                    <th>Tujuan Fakultas</th>
                                    <th>Tujuan Prodi</th>
                                    <th>Univ Asal</th>
                                    <th>Negara Asal</th>
                                    <th>Program</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Created Date</th>
                                    <th>Foto</th>
                                    <th>Passport</th>
                                    <th>Student ID</th>
                                    <th>LoA</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td>{{ $item->unit_kerja_text ?? '-' }}</td>
                                    <td>{{ $item->jenjang ?? '-' }}</td>
                                    <td>{{ $item->prodi_asal ?? '-' }}</td>
                                    <td>{{ $item->fakultas_asal ?? '-' }}</td>
                                    <td>{{ $item->jenis_kelamin ?? '-' }}</td>
                                    <td>{{ $item->via ?? '-' }}</td>
                                    <td>{{ $item->tipe_text ?? '-' }}</td>
                                    <td>{{ $item->tujuan_fakultas_unit_text ?? '-' }}</td>
                                    <td>{{ $item->tujuan_prodi_text ?? '-' }}</td>
                                    <td>{{ $item->univ_asal_text ?? '-' }}</td>
                                    <td>{{ $item->negara_asal_text ?? '-' }}</td>
                                    <td>{{ $item->program_text ?? '-' }}</td>
                                    <td>{{ $item->jenis_kegiatan_text ?? '-' }}</td>
                                    <td>{{ $item->created_date ?? '-' }}</td>
                                    <td>{{ $item->foto ?? '-' }}</td>
                                    <td>{{ $item->passport ?? '-' }}</td>
                                    <td>{{ $item->student_id ?? '-' }}</td>
                                    <td>{{ $item->loa ?? '-' }}</td>
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
                                    <th>Nama</th>
                                    <th>Unit Kerja</th>
                                    <th>Jenjang</th>
                                    <th>Prodi Asal</th>
                                    <th>Fakultas Asal</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Via</th>
                                    <th>Full/Part Time</th>
                                    <th>Tujuan Fakultas</th>
                                    <th>Tujuan Prodi</th>
                                    <th>Univ Asal</th>
                                    <th>Negara Asal</th>
                                    <th>Program</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Created Date</th>
                                    <th>Foto</th>
                                    <th>Passport</th>
                                    <th>Student ID</th>
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
        <!-- Individual column searching (text inputs) Ends -->
    </div>
</div>

@endsection
