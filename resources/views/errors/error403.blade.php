@extends('errors.master')

@section('title')Error
@endsection

@push('css')
@endpush

@section('content')
<div class="page-wrapper" id="pageWrapper">        
    <div class="error-wrapper">
      <div class="container">
        <div class="error-page1">
          
          <div class="col-md-8 offset-md-2">
            <h3>403 - Unauthorized Actions</h3>
            <p class="sub-content">The page you are attempting to reach is currently not available. This may be because the page does not exist or has been moved.</p>
            <a class="btn btn-primary btn-lg" href="{{ route('back.home') }}">Back to Homepage</a>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection