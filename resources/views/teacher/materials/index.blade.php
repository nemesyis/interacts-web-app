@extends('layouts.teacher')

@section('title', 'Materials - ' . $appointment->appointment_title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('teacher.appointments', $appointment->classroom_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Appointments
            </a>
            <h1 class="h3 mb-0">{{ $appointment->appointment_title }}</h1>
            <p class="text-muted">
                {{ $appointment->classroom->classroom_name }} • 
                Appointment #{{ $appointment->appointment_number }}
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Upload Material Form -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Upload New Material</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.materials.store', $appointment->appointment_id) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="material_title" class="form-label">Material Title <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('material_title') is-invalid @enderror" 
                                id="material_title" 
                                name="material_title" 
                                value="{{ old('material_title') }}" 
                                required
                            >
                            @error('material_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="material_type" class="form-label">Material Type <span class="text-danger">*</span></label>
                            <select 
                                class="form-select @error('material_type') is-invalid @enderror" 
                                id="material_type" 
                                name="material_type" 
                                required
                                onchange="toggleFileInput(this.value)"
                            >
                                <option value="">-- Select Type --</option>
                                <option value="video" {{ old('material_type') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="pdf" {{ old('material_type') == 'pdf' ? 'selected' : '' }}>PDF Document</option>
                                <option value="slides" {{ old('material_type') == 'slides' ? 'selected' : '' }}>Slides/Presentation</option>
                                <option value="document" {{ old('material_type') == 'document' ? 'selected' : '' }}>Document</option>
                                <option value="link" {{ old('material_type') == 'link' ? 'selected' : '' }}>External Link</option>
                            </select>
                            @error('material_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mb-3" id="file_upload_section">
                            <label for="file" class="form-label">Upload File <span class="text-danger">*</span></label>
                            <input 
                                type="file" 
                                class="form-control @error('file') is-invalid @enderror" 
                                id="file" 
                                name="file"
                            >
                            <small class="text-muted">Maximum file size: 50MB</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Link URL -->
                        <div class="mb-3" id="link_url_section" style="display: none;">
                            <label for="file_url" class="form-label">External Link <span class="text-danger">*</span></label>
                            <input 
                                type="url" 
                                class="form-control @error('file_url') is-invalid @enderror" 
                                id="file_url" 
                                name="file_url" 
                                placeholder="https://example.com"
                            >
                            @error('file_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-muted">(Optional)</span></label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="3"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-upload me-2"></i>Upload Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Materials List -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Uploaded Materials</h5>
                </div>
                <div class="card-body">
                    @if($materials->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($materials as $material)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-primary me-2">
                                                    @switch($material->material_type)
                                                        @case('video')
                                                            <i class="bi bi-camera-video"></i> Video
                                                            @break
                                                        @case('pdf')
                                                            <i class="bi bi-file-pdf"></i> PDF
                                                            @break
                                                        @case('slides')
                                                            <i class="bi bi-file-slides"></i> Slides
                                                            @break
                                                        @case('document')
                                                            <i class="bi bi-file-text"></i> Document
                                                            @break
                                                        @case('link')
                                                            <i class="bi bi-link-45deg"></i> Link
                                                            @break
                                                    @endswitch
                                                </span>
                                                <h6 class="mb-0">{{ $material->material_title }}</h6>
                                            </div>
                                            @if($material->description)
                                                <p class="text-muted small mb-2">{{ $material->description }}</p>
                                            @endif
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                Uploaded {{ $material->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="ms-3">
                                            <a href="{{ $material->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('teacher.materials.delete', $material->material_id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this material?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-x fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Materials Yet</h5>
                            <p class="text-muted">Upload your first material to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleFileInput(type) {
    const fileSection = document.getElementById('file_upload_section');
    const linkSection = document.getElementById('link_url_section');
    const fileInput = document.getElementById('file');
    const linkInput = document.getElementById('file_url');
    
    if (type === 'link') {
        fileSection.style.display = 'none';
        linkSection.style.display = 'block';
        fileInput.removeAttribute('required');
        linkInput.setAttribute('required', 'required');
    } else if (type !== '') {
        fileSection.style.display = 'block';
        linkSection.style.display = 'none';
        fileInput.setAttribute('required', 'required');
        linkInput.removeAttribute('required');
    } else {
        fileSection.style.display = 'block';
        linkSection.style.display = 'none';
        fileInput.removeAttribute('required');
        linkInput.removeAttribute('required');
    }
}

// Initialize on page load if there's an old value
document.addEventListener('DOMContentLoaded', function() {
    const materialType = document.getElementById('material_type').value;
    if (materialType) {
        toggleFileInput(materialType);
    }
});
</script>
@endsection