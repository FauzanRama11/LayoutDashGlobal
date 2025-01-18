@extends('layouts.master') 

@section(section: 'content') 
    <h2>Upload Pelaporan</h2>
    <p>This is Upload Pelaporan.</p>

    <div class="card-body">
    <form class="was-validated" action =  "{{ route('bukti.update', $data->id)  }} " method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($data))
            @method('PUT') 
        @endif

        <div class="mb-3">
          <label class="form-label" for="linkPelaporan">Upload Pelaporan</label>
            @if (isset($data) && !empty($data->link_pelaporan))
                <div class="mb-2">
                    <a href="{{ route('view_naskah.pdf', basename($data->link_pelaporan)) }}" target="_blank" class="btn btn-primary">
                      View / Download Document
                    </a>
                </div>                              
                  <input class="form-control" type="file" name="linkPelaporan" accept=".pdf" onchange="validateFileSize(this)">
                  <div class="mb-2">
                    <small>Current file: {{ basename($data->link_pelaporan) }}</small>
                  </div>
              @else
                <input class="form-control" type="file" name="linkPelaporan" accept=".pdf" onchange="validateFileSize(this)" required>
              @endif
        </div>
  </div>

        <button class="btn btn-primary" type="submit">Submit</button>
      </div>
    </form>
  </div>
</div>
<script>
    function validateFileSize(input) {
        const file = input.files[0];
        if (file) {
            const maxSize = 1 * 1024 * 1024; 
            if (file.size > maxSize) {
                alert("File size exceeds 1 MB!");
                input.value = ""; // Clear the input
            }
        }
    }
</script>
@endsection