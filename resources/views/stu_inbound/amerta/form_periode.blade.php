@extends('layouts.master') 

@section('content') 
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Form Periode Amerta</h5>
                    <span>This is Optional Notes</span>
                </div>
                <div class="card-body">
                    <form class="was-validated" method="POST" action="{{ isset($periode) ? route('am_update_periode', $periode->id) : route('am_tambah_periode') }}">
                        @csrf
                        @if(isset($periode))
                            @method('PUT')
                        @endif
                
                        <div class="row">
                            <!-- Kolom Pendaftaran -->
                            <div class="col-md-6">
                                <h6>Pendaftaran</h6>
                                <div class="mb-3">
                                    <label class="form-label" for="start_date_pendaftaran">Start Date</label>
                                    <input type="date" class="form-control is-invalid" id="start_date_pendaftaran" name="start_date_pendaftaran" value="{{ old('start_date_pendaftaran', $periode->start_date_pendaftaran ?? '') }}" required>
                                    <div class="invalid-feedback">Start Date is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="end_date_pendaftaran">End Date</label>
                                    <input type="date" class="form-control is-invalid" id="end_date_pendaftaran" name="end_date_pendaftaran" value="{{ old('end_date_pendaftaran', $periode->end_date_pendaftaran ?? '') }}" required>
                                    <div class="invalid-feedback">End Date is required.</div>
                                </div>
                            </div>
                
                            <!-- Kolom Program -->
                            <div class="col-md-6">
                                <h6>Program</h6>
                                <div class="mb-3">
                                    <label class="form-label" for="start_date_program">Start Date</label>
                                    <input type="date" class="form-control is-invalid" id="start_date_program" name="start_date_program" value="{{ old('start_date_program', $periode->start_date_program ?? '') }}" required>
                                    <div class="invalid-feedback">Start Date is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="end_date_program">End Date</label>
                                    <input type="date" class="form-control is-invalid" id="end_date_program" name="end_date_program" value="{{ old('end_date_program', $periode->end_date_program ?? '') }}" required>
                                    <div class="invalid-feedback">End Date is required.</div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Tombol Submit -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Save</button>
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

