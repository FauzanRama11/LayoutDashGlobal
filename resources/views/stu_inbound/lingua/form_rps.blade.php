@extends('layouts.master') 

@section('content') 
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Upload Template RPS</h5>
                    <span>This is Optional Notes</span>
                </div>
                <div class="card-body">
                    <form class="was-validated" method="POST" action="{{ isset($rps) ? route('li_update_rps', $rps->id) : route('li_tambah_rps') }}"  enctype="multipart/form-data">
                        @csrf
                        @if(isset($rps))
                            @method('PUT')
                        @endif
                
                        <div class="mb-3">
                            <label class="form-label">Upload File</label>
                        
                            @if (isset($rps) && !empty($rps->url_attachment))
                                <!-- Tampilkan tautan untuk download atau view file -->
                                <div class="mb-2">
                                    <a href="{{ route('view.pdf', basename($rps->url_attachment)) }}" target="_blank" class="btn btn-primary">
                                        View / Download Document
                                    </a>
                                </div>
                        
                                <!-- Tampilkan input file untuk mengganti file -->
                                <input class="form-control @error('url_attachment') is-invalid @enderror" type="file" name="url_attachment">
                                <div class="form-text">Upload a new file to replace the existing document (optional).</div>
                                @error('url_attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @else
                                <!-- Input file jika ID tidak ada -->
                                <input class="form-control @error('url_attachment') is-invalid @enderror" type="file" name="url_attachment">
                                @error('url_attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                
                        <!-- Checkbox for is_active -->
                        <div class="media mb-3 align-items-center">
                            <label class="col-form-label me-3">Status</label>
                            <div class="media-body text-end icon-state d-flex align-items-center">
                                <!-- Input hidden sebagai fallback -->
                                <input type="hidden" name="is_active" value="N">
                            
                                <!-- Checkbox -->
                                <label class="switch me-2">
                                    <input type="checkbox" id="is_active_checkbox" name="is_active" value="Y" 
                                           {{ old('is_active', $rps->is_active ?? '') === 'Y' ? 'checked' : '' }}
                                           onchange="toggleStatusText()">
                                    <span class="switch-state"></span>
                                </label>
                            
                                <!-- Status Text -->
                                <span id="status_text">{{ old('is_active', $rps->is_active ?? '') === 'Y' ? 'Aktif' : 'Tidak Aktif' }}</span>
                            </div>
                        </div>
                
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
                
            </div>
@endsection

<script>
   
   function toggleStatusText() {
        const checkbox = document.getElementById('is_active_checkbox');
        const statusText = document.getElementById('status_text');

        // Periksa apakah checkbox dicentang, dan ubah teks
        if (checkbox.checked) {
            statusText.textContent = 'Aktif';
        } else {
            statusText.textContent = 'Tidak Aktif';
        }
    }

</script>

