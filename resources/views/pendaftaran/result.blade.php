@extends('pendaftaran.master')


@section('content')

<div class="page-body vh-100 d-flex justify-content-center align-items-center">
    <div class="container-fluid mx-auto">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4 d-flex justify-content-center align-items-center m-3 mb-md-0">
                @if($type === 'amerta')
                    <img src="{{ asset('assets/images/LogoAmerta.png') }}" alt="Logo Amerta" class="img-fluid" style="width: 240px; height: auto;">
                @elseif($type === 'lingua')
                    <img src="{{ asset('assets/images/LogoLingua.png') }}" alt="Logo Lingua" class="img-fluid" style="width: 240px; height: auto;">
                @else
                    <img src="{{ asset('assets/images/LogoAGE.png') }}" alt="Logo AGE" class="img-fluid" style="width: 240px; height: auto;">
                @endif
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="text-center">{{ $title }}</h5>
                        <p class="text-center">{{ $message }}</p>
                        <hr>
                    </div>
                    <div class="card-body">
                        <p class="text-center">If you have something to discuss, feel free to contact our contact person in 082112343433</p>
                    
                        <div class="text-center">
                            {{-- Jika tipe adalah amerta atau lingua --}}
                            @if($type === 'amerta' || $type === 'lingua')
                                <a href="{{ url('/') }}" class="btn btn-secondary">Go Back to Homepage</a>
                            @else
                                {{-- Jika tipe bukan amerta atau lingua --}}
                                <a href="{{ route('registrasi', ['type' => 'amerta']) }}" class="btn btn-primary m-2">
                                    Go to Amerta Registration
                                </a>
                                <a href="{{ route('registrasi', ['type' => 'lingua']) }}" class="btn btn-primary m-2">
                                    Go to Lingua Registration
                                </a>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
