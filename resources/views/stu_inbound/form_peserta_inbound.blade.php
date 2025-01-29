@extends('layouts.master')

@section('content') 

<div class="card">
  <div class="card-header pb-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Bagian Kiri: Judul dan Span -->
      <div>
          <h5 class="mb-0">Your Title Here</h5>
          <span>This is Optional Notes</span>
      </div>

      <!-- Bagian Kanan: Tombol -->
      <div class="d-flex row">
        <form  action="{{ route('approve_peserta_inbound', ['id' => $processedData['id'] ]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            <button type="submit" class="btn btn-primary edit-button">Approve</button>
        </form>
    
        <form  action="{{ route('reject_peserta_inbound', ['id' => $processedData['id'] ]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            <button type="submit" class="btn btn-danger edit-button">Reject</button>
        </form> 
      </div>
    </div>
    <hr>
  </div>
  <div class="card-body">
    <form class="was-validated" action="{{ route('program_stuin.store') }}" method="post" enctype="multipart/form-data">
      @csrf

        <!-- Type -->
        <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="amerta" {{ old('type', $processedData['type'] ?? '') === 'amerta' ? 'selected' : '' }}>Amerta</option>
                <option value="lingua" {{ old('type', $processedData['type'] ?? '') === 'lingua' ? 'selected' : '' }}>Lingua</option>
            </select>
            <div class="invalid-feedback">Type wajib dipilih.</div>
        </div>

        <!-- Selected Program -->
        <div class="mb-3">
            <label class="form-label" for="sel_prog">Selected Program</label>
            <select class="form-select" id="sel_prog" name="sel_prog" required>
                <option value="amerta" {{ old('sel_prog', $processedData['metadata']['selected_program'] ?? '') === 'amerta' ? 'selected' : '' }}>Amerta</option>
                <option value="lingua" {{ old('sel_prog', $processedData['metadata']['selected_program'] ?? '') === 'lingua' ? 'selected' : '' }}>Lingua</option>
            </select>
            <div class="invalid-feedback">Selected Program wajib dipilih.</div>
        </div>

        <!-- Intended Mobility -->
        <div class="mb-3">
            <label class="form-label" for="mobility">Intended Mobility</label>
            <select class="form-select" id="mobility" name="mobility" required>
                <option value="vm" {{ old('mobility', $processedData['metadata']['mobility'] ?? '') === 'vm' ? 'selected' : '' }}>Virtual Mobility</option>
                <option value="m" {{ old('mobility', $processedData['metadata']['mobility'] ?? '') === 'm' ? 'selected' : '' }}>Mobility</option>
            </select>
            <div class="invalid-feedback">Mobility wajib dipilih.</div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <textarea class="form-control" id="email" name="email" required>{{ old('email', $processedData['email'] ?? '') }}</textarea>
            <div class="invalid-feedback">Email wajib diisi.</div>
        </div>

        <!-- Secondary Email -->
        <div class="mb-3">
            <label class="form-label" for="secondary_email">Secondary Email</label>
            <textarea class="form-control" id="secondary_email" name="secondary_email" required>{{ old('secondary_email', $processedData['secondary_email'] ?? '') }}</textarea>
            <div class="invalid-feedback">Email wajib diisi.</div>
        </div>
            



    
        <!-- Tombol Submit -->
        <div class="text-end mt-4">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
        <td>
            {{-- <form  action="{{ route('', ['id' => $item->id]) }}" method="GET">
                <button type="submit" class="btn btn-primary edit-button">Edit</button>
            </form> --}}
        </td>
    
    
    </form>
  </div>
</div>


<div class="card">
  <div class="card-body">
    <form class="was-validated" action="{{ route('program_stuin.store') }}" method="post" enctype="multipart/form-data">
      @csrf

        <!-- Type -->
        <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="amerta" {{ old('type', $processedData['type'] ?? '') === 'amerta' ? 'selected' : '' }}>Amerta</option>
                <option value="lingua" {{ old('type', $processedData['type'] ?? '') === 'lingua' ? 'selected' : '' }}>Lingua</option>
            </select>
            <div class="invalid-feedback">Type wajib dipilih.</div>
        </div>

        <!-- Selected Program -->
        <div class="mb-3">
            <label class="form-label" for="sel_prog">Selected Program</label>
            <select class="form-select" id="sel_prog" name="sel_prog" required>
                <option value="amerta" {{ old('sel_prog', $processedData['metadata']['selected_program'] ?? '') === 'amerta' ? 'selected' : '' }}>Amerta</option>
                <option value="lingua" {{ old('sel_prog', $processedData['metadata']['selected_program'] ?? '') === 'lingua' ? 'selected' : '' }}>Lingua</option>
            </select>
            <div class="invalid-feedback">Selected Program wajib dipilih.</div>
        </div>

        <!-- Intended Mobility -->
        <div class="mb-3">
            <label class="form-label" for="mobility">Intended Mobility</label>
            <select class="form-select" id="mobility" name="mobility" required>
                <option value="vm" {{ old('mobility', $processedData['metadata']['mobility'] ?? '') === 'vm' ? 'selected' : '' }}>Virtual Mobility</option>
                <option value="m" {{ old('mobility', $processedData['metadata']['mobility'] ?? '') === 'm' ? 'selected' : '' }}>Mobility</option>
            </select>
            <div class="invalid-feedback">Mobility wajib dipilih.</div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <textarea class="form-control" id="email" name="email" required>{{ old('email', $processedData['email'] ?? '') }}</textarea>
            <div class="invalid-feedback">Email wajib diisi.</div>
        </div>

        <!-- Secondary Email -->
        <div class="mb-3">
            <label class="form-label" for="secondary_email">Secondary Email</label>
            <textarea class="form-control" id="secondary_email" name="secondary_email" required>{{ old('secondary_email', $processedData['secondary_email'] ?? '') }}</textarea>
            <div class="invalid-feedback">Email wajib diisi.</div>
        </div>
            



    
        <!-- Tombol Submit -->
        <div class="text-end mt-4">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
        <td>
            {{-- <form  action="{{ route('', ['id' => $item->id]) }}" method="GET">
                <button type="submit" class="btn btn-primary edit-button">Edit</button>
            </form> --}}
        </td>
    
    
    </form>
  </div>
</div>
@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const jenisSelect = document.getElementById('jenisSelect');
    const regSection = document.querySelector('.reg');
    const regInputs = regSection.querySelectorAll('input, select, textarea'); 
    
    jenisSelect.addEventListener('change', function () {
      if (this.value === 'Tidak') {
        regSection.style.display = 'block';
        regInputs.forEach(input => input.setAttribute('required', '')); 
      } else {
        regSection.style.display = 'none';
        regInputs.forEach(input => input.removeAttribute('required')); 
      }
    });
  });
  </script>
