@extends('layouts.master') 
@section('content') 



                <div class="card">
                  <div class="card-header pb-0">
                    <h5>Form Program {{ Auth::user()->name}}</h5><span>This is Optional Notes</span>
                  </div>
                  <div class="card-body">
                    <form class="was-validated">
                    <div class="mb-3">
                      <label class="form-label" for="jenisSelect">Jenis</label>
                        <select class="form-select" id = "jenisSelect" required="" aria-label="select example">
                          <option value="Pelaporan">Pelaporan</option>
                          <option value="Registrasi">Registrasi</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="validationTextarea">Name</label>
                        <input class="form-control is-invalid" id="validationTextarea" placeholder="Nama Program" required=""></i>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="validationTextarea">Start Date</label>
                        <input type="date" class="form-control is-invalid" id="validationTextarea" placeholder="Required example textarea" required=""></input>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="validationTextarea">End Date</label>
                        <input type="date" class="form-control is-invalid" id="validationTextarea" placeholder="Required example textarea" required=""></input>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="reg" style="display: none;">
                        <div class="card-header pb-0">
                          <h5>Detail Registrasi</h5><span>Detail Registrasi Program</span>
                        </div>
                        <div class="card-body">
                          <div class="mb-3">
                            <label class="form-label" for="regOpen">Reg Date Open</label>
                            <input type="date" class="form-control" id="regOpen" required>
                            <div class="invalid-feedback"></div>
                          </div>
                          <div class="mb-3">
                            <label class="form-label" for="regClose">Reg Date Close</label>
                            <input type="date" class="form-control" id="regClose" required>
                            <div class="invalid-feedback"></div>
                          </div>
                          <div class="mb-3">
                            <label class="form-label" for="validationTextarea">Program Description</label>
                            <textarea class="form-control is-invalid" id="validationTextarea" placeholder="Program Description" required=""></textarea>
                            <div class="invalid-feedback"></div>
                          </div>
                          <div class="mb-3">
                            <label class="form-label" for="validationTextarea">Logo/Poster</label>
                            <input class="form-control" type="file" aria-label="file example" required="">
                          <div class="invalid-feedback"></div>
                          </div>
                        </div>
                      </div>
                      <div class="mb-2">
                        <label class="form-label">Category</label>
                        <select class="js-example-basic-single col-sm-12">
                        @foreach($category as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="validationTextarea">Host Unit</label>
                        <input class="form-control is-valid" id="validationTextarea" placeholder='{{ Auth::user()->name}}'  readonly></i>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-2">
                        <label class="form-label">PIC</label>
                        <select class="js-example-basic-single col-sm-12">
                            <option value="AL">Alabama</option>
                            <option value="WY">Wyoming</option>
                            <option value="AL">Wales</option>
                            <option value="WY">America</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="validationTextarea">Corresponding Email</label>
                        <input type = "email" class="form-control is-invalid" id="validationTextarea" placeholder="Email" required=""></i>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-2">
                        <label class="form-label">Negara Tujuan</label>
                        <div class="js-example-basic-single col-sm-12">
                          @foreach ($negara as $item )
                            <option value="{{$item -> id}}">{{$item -> name}}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-2">
                        <label class="form-label">Universitas Tujuan</label>
                        <select class="js-example-basic-single col-sm-12">
                            <option value="AL">Alabama</option>
                            <option value="WY">Wyoming</option>
                            <option value="AL">Wales</option>
                            <option value="WY">America</option>
                            <div class="invalid-feedback"></div>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="validationTextarea">Website</label>
                        <input class="form-control is-invalid" id="validationTextarea" placeholder="Website" required=""></i>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="mb-3">
                      <label class="form-label" for="validationTextarea">Via</label>
                        <select class="form-select" required="" aria-label="select example">
                          <option value="Registrasi">Offline</option>
                          <option value="Pelaporan">Online</option>
                          <option value="Pelaporan">Hybrid</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                    </form>
                  </div>
                </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
      const jenisSelect = document.getElementById('jenisSelect');
      const regSection = document.querySelector('.reg');

      jenisSelect.addEventListener('change', function () {
        if (this.value === 'Registrasi') {
          regSection.style.display = 'block';
        } else {
          regSection.style.display = 'none';
        }
      });
    });
  </script>

