@extends('layouts.master') 

@section('content') 
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css')}}">
@endpush
    
    <h2>Student Inbound | Lingua</h2>
    <p>This is the Template RPS.</p>

        <div class="container-fluid">
            <div class="row">
                <!-- Individual column searching (text inputs) Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="API-2">
                                    <a href="{{ route('li_form_rps.create') }}">
                                        <button class="btn btn-success btn-sm active" type="button" style="width: 20%; margin:15px">+ Tambah</button>
                                    </a>
                                    <thead>
                                        <tr>
                                            <th>Attachment</th>
                                            <th>Status</th>
                                            <th>Tgl Upload</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item->url_attachment ?? '-' }}</td>
                                            <td>
                                                @if ($item->is_active === 'Y')
                                                    <span class="badge badge-primary">Aktif</span>
                                                @else
                                                    <span class="badge badge-info">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_date)->format('d M Y - H:i') ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('li_
                                                
form_rps.edit', ['id' => $item->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                            </td>
                                            <td>
                                                <form action="{{ route('li_
                                                
hapus_rps', ['id' => $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr> 
                                            <th>Attachment</th>
                                            <th>Status</th>
                                            <th>Tgl Upload</th>
                                            <th></th>
                                            <th></th>
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