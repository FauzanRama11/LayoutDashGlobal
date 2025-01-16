@extends('layouts.master') 
@section('content') 

                      <div class="mb-2">
                        <label class="col-form-label">Select2 single select</label>
                        <select class="js-example-basic-single col-sm-12">
                            <option value="AL">Alabama</option>
                            <option value="WY">Wyoming</option>
                            <option value="AL">Wales</option>
                            <option value="WY">America</option>
                        </select>
                      </div>
                     
@endsection



<script src="../assets/js/select2/select2.full.min.js"></script>
<script src="../assets/js/select2/select2-custom.js"></script>