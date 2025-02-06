@extends('layouts.master') 

@section('content') 
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Form Target</h5>
                    <span>This is Optional Notes</span>
                </div>
                <div class="card-body">
                    <form class="was-validated" method="POST" action="{{ isset($target) ? route('update_target', $target->id) : route('tambah_target') }}">
                        @csrf
                        @if(isset($target))
                            @method('PUT')
                        @endif
                
                        <div class="row">
                            <!-- Kolom kiri -->
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label" for="fakultas">Fakultas</label>
                                    <select class="form-select js-example-basic-single" id="fakultas" name="fakultas" required>
                                        @foreach($unit as $item)
                                            <option value="{{ $item->id }}" {{ old('fakultas', $target->id_fakultas ?? null) == $item->id ? 'selected' : '' }}>{{ $item->nama_ind }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="year">Year</label>
                                    <input type="number" class="form-control " id="year" name="year" value="{{ old('year', $target->year ?? '') }}" required>
                                    <div class="invalid-feedback">End Date is required.</div>
                                </div>
                            </div>
                
                            <!-- Kolom kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="target_pt">Target PT</label>
                                    <input type="number" class="form-control" id="target_pt" name="target_pt" value="{{ old('target_pt', $target->target_pt ?? '') }}" required>
                                    <div class="invalid-feedback">Start Date is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="target_ft">Target FT</label>
                                    <input type="number" class="form-control" id="target_ft" name="target_ft" value="{{ old('target_ft', $target->target_ft ?? '') }}" required>
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
// document.querySelectorAll("form input").forEach(input => {
//     input.addEventListener("input", function () {
//         if (input.value === "") {
//             input.classList.add("is-invalid");
//             input.nextElementSibling.textContent = "Field ini wajib diisi.";
//         } else {
//             input.classList.remove("is-invalid");
//             input.nextElementSibling.textContent = "";
//         }
//     });
// });
   
</script>

