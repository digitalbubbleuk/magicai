@extends('panel.layout.app')
@section('title', __('Add New Template'))

@section('content')
    <div class="page-header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <a href="{{ route('dashboard.admin.creative-suite.templates.index') }}" class="page-pretitle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <polyline points="15,6 9,12 15,18"/>
                        </svg>
                        {{ __('Creative Suite Templates') }}
                    </a>
                    <h2 class="page-title">
                        {{ __('Add New Template') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('dashboard.admin.creative-suite.templates.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Template Details') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Template Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name') }}" placeholder="{{ __('Enter template name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Category') }} <span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                                <option value="">{{ __('Select a category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Preview Image') }} <span class="text-danger">*</span></label>
                                            <input type="file" name="preview" class="form-control @error('preview') is-invalid @enderror" 
                                                   accept="image/*" required onchange="previewImage(this, 'preview-img')">
                                            <small class="form-hint">{{ __('Upload a preview image for the template (JPEG, PNG, GIF, max 2MB)') }}</small>
                                            @error('preview')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="preview-img" src="#" alt="Preview" style="max-width: 200px; max-height: 150px; display: none;" class="img-thumbnail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Template Images') }}</label>
                                            <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" 
                                                   accept="image/*" multiple onchange="previewMultipleImages(this, 'images-preview')">
                                            <small class="form-hint">{{ __('Upload additional images used in the template (optional)') }}</small>
                                            @error('images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="images-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Template Data') }} <span class="text-danger">*</span></label>
                                    <textarea name="template_data" class="form-control @error('template_data') is-invalid @enderror" 
                                              rows="10" placeholder="{{ __('Paste the template JSON data here') }}" required>{{ old('template_data') }}</textarea>
                                    <small class="form-hint">
                                        {{ __('Paste the exported JSON data from Creative Suite. You can export a template from the Creative Suite editor.') }}
                                    </small>
                                    @error('template_data')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <h4 class="alert-title">{{ __('How to create a template:') }}</h4>
                                    <div class="text-muted">
                                        <ol class="mb-0">
                                            <li>{{ __('Create your design in the Creative Suite editor') }}</li>
                                            <li>{{ __('Use the export functionality to get the JSON data') }}</li>
                                            <li>{{ __('Copy the JSON data and paste it in the "Template Data" field above') }}</li>
                                            <li>{{ __('Upload a preview image that represents your template') }}</li>
                                            <li>{{ __('If your template uses custom images, upload them in the "Template Images" field') }}</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="d-flex">
                                    <a href="{{ route('dashboard.admin.creative-suite.templates.index') }}" class="btn btn-link">{{ __('Cancel') }}</a>
                                    <button type="submit" class="btn btn-primary ms-auto">{{ __('Create Template') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }

        function previewMultipleImages(input, previewContainerId) {
            const container = document.getElementById(previewContainerId);
            container.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.maxWidth = '100px';
                        img.style.maxHeight = '75px';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        // Validate JSON on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const dataField = document.querySelector('textarea[name="data"]');
            try {
                JSON.parse(dataField.value);
            } catch (error) {
                e.preventDefault();
                alert('{{ __("Invalid JSON data. Please check your template data.") }}');
                dataField.focus();
            }
        });
    </script>
@endsection
