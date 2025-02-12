@extends('pendaftaran.master')


@section('content')

<div class="page-body vh-100 d-flex justify-content-center align-items-center">
    <div class="container-fluid mx-auto">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4 d-flex justify-content-center align-items-center m-3 mb-md-0">
                @if($program->logo_base64)
                    <img src="{{ $program->logo_base64 }}" alt="Preview" class="img-fluid" style="max-width:100%; height: 300px; object-fit: cover;">
                @else
                    <p>Logo tidak tersedia.</p>
                @endif
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        
                        <h5  class="text-center">{{ $program->name }}</h5>
                        <span  class="text-center">{{ $message }}</span>
                        <hr>
                    </div>
                    <div class="card-body">
                        <p class="text-center">If you have something to discuss, feel free to contact our contact person in 082112343433</p>
                    
                        <div class="text-center">
                            <a href="{{ url('/') }}" class="btn btn-secondary">Go Back to Homepage</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
