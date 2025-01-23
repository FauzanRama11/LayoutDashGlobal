@extends('layouts.master') 

@section('content') 
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5>{{ isset($matkul) ? 'Edit Mata Kuliah Amerta' : 'Form Mata Kuliah Amerta' }}</h5>
                                <span>This is Optional Notes</span>
                            </div>
                            <div class="card-body">
                                <form class="was-validated" method="POST" enctype="multipart/form-data" 
                                      action="{{ isset($matkul) ? route('am_update_matkul', $matkul->id) : route('am_tambah_matkul') }}">
                                    @csrf
                                    @if(isset($matkul))
                                        @method('PUT')
                                    @endif
                        
                                    <div class="row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prodi</label>
                                                <select class="form-select" name="id_prodi" required>
                                                    @foreach($prodi as $item)
                                                        <option value="{{ $item->id }}" 
                                                            {{ old('id_prodi', $matkul->id_prodi ?? '') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Please select a valid Prodi.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Periode</label>
                                                <select class="form-select" name="id_age_amerta" required>
                                                    @foreach($periode as $item)
                                                        <option value="{{ $item->id }}" 
                                                            {{ old('id_age_amerta', $matkul->id_age_amerta ?? '') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->date_program }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Please select a valid Periode.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Course ID (as in Cybercampus)</label>
                                                <input class="form-control" name="code" 
                                                       value="{{ old('code', $matkul->code ?? '') }}" 
                                                       placeholder="Enter Course ID" required>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Course Title</label>
                                                <input class="form-control" name="title" 
                                                       value="{{ old('title', $matkul->title ?? '') }}" 
                                                       placeholder="Enter Course Title" required>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Course Semester</label>
                                                <input class="form-control digits" type="number" name="semester" 
                                                       value="{{ old('semester', $matkul->semester ?? '') }}" 
                                                       placeholder="Enter Course Semester" required>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Lecturer Name(s) with Titles</label>
                                                <textarea class="form-control" name="lecturers" 
                                                          placeholder="Enter Lecturer Names" required>{{ old('lecturers', $matkul->lecturers ?? '') }}</textarea>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Course Credits</label>
                                                <div class="input-group">
                                                    <input class="form-control digits" type="number" name="sks" 
                                                           value="{{ old('sks', $matkul->sks ?? '') }}" 
                                                           placeholder="Enter Credits" required>
                                                    <span class="input-group-text">sks</span>
                                                </div>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Capacity</label>
                                                <input class="form-control digits" type="number" name="capacity" 
                                                       value="{{ old('capacity', $matkul->capacity ?? '') }}" 
                                                       placeholder="Enter Capacity" required>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Status Matkul</label>
                                                <select class="form-select" name="status" required>
                                                    <option value="approved" {{ old('status', $matkul->status ?? '') == 'approved' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="" {{ old('status', $matkul->status ?? '') == '' ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                                <div class="invalid-feedback">Please select a valid status.</div>
                                            </div>
                                        </div>
                        
                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="description" 
                                                          placeholder="Enter Course Description" required>{{ old('description', $matkul->description ?? '') }}</textarea>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Schedule</label>
                                                <textarea class="form-control" name="schedule" 
                                                          placeholder="Contoh: Monday (10.30-12.20), Tuesday (07.00-08.40)" required>{{ old('schedule', $matkul->schedule ?? '') }}</textarea>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label class="form-label">Attachment (Optional)</label>
                                            
                                                @if (isset($matkul) && !empty($matkul->url_attachment))
                                                    <!-- Tampilkan tautan untuk download atau view file -->
                                                    <div class="mb-2">
                                                        <a href="{{ route('view.pdf', basename($matkul->url_attachment)) }}" target="_blank" class="btn btn-primary">
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
                                                    <div class="form-text">Upload a file if needed.</div>
                                                    @error('url_attachment')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
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
