@extends('panel.layout.app')
@section('title', __('Edit Template'))

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
                        {{ __('Edit Template') }}: {{ $template->name }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('dashboard.admin.creative-suite.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                                                   value="{{ old('name', $template->name) }}" placeholder="{{ __('Enter template name') }}" required>
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
                                                    <option value="{{ $category->id }}" {{ old('category_id', $template->category_id) == $category->id ? 'selected' : '' }}>
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
                                            <label class="form-label">{{ __('Preview Image') }}</label>
                                            <input type="file" name="preview" class="form-control @error('preview') is-invalid @enderror" 
                                                   accept="image/*" onchange="previewImage(this, 'preview-img')">
                                            <small class="form-hint">{{ __('Upload a new preview image (leave empty to keep current image)') }}</small>
                                            @error('preview')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                @if($template->preview_image)
                                                    <div class="mb-2">
                                                        <strong>{{ __('Current Preview:') }}</strong><br>
                                                        @php
                                                            $previewUrl = $template->preview_image;
                                                            // Fix localhost URLs to use the correct server address
                                                            if (str_starts_with($previewUrl, 'localhost/')) {
                                                                $previewUrl = request()->getSchemeAndHttpHost() . '/' . substr($previewUrl, 10);
                                                            } elseif (str_starts_with($previewUrl, 'http://localhost/')) {
                                                                $previewUrl = str_replace('http://localhost/', request()->getSchemeAndHttpHost() . '/', $previewUrl);
                                                            }
                                                        @endphp
                                                        <img src="{{ $previewUrl }}" alt="Current Preview" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                                                    </div>
                                                @endif
                                                <img id="preview-img" src="#" alt="New Preview" style="max-width: 200px; max-height: 150px; display: none;" class="img-thumbnail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Additional Template Images') }}</label>
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
                                              rows="10" placeholder="{{ __('Paste the template JSON data here') }}" required>{{ old('template_data', is_array($template->template_data) ? json_encode($template->template_data, JSON_PRETTY_PRINT) : $template->template_data) }}</textarea>
                                    <small class="form-hint">
                                        {{ __('Update the template JSON data. You can export updated data from the Creative Suite editor.') }}
                                    </small>
                                    @error('template_data')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <h4 class="alert-title">{{ __('Template Information:') }}</h4>
                                    <div class="text-muted">
                                        <p class="mb-1"><strong>{{ __('Created:') }}</strong> {{ $template->created_at->format('M d, Y \a\t H:i') }}</p>
                                        <p class="mb-1"><strong>{{ __('Last Updated:') }}</strong> {{ $template->updated_at->format('M d, Y \a\t H:i') }}</p>
                                        @if($template->category)
                                            <p class="mb-0"><strong>{{ __('Current Category:') }}</strong> 
                                                <span class="badge bg-{{ $template->category->color ?? 'primary' }}">{{ $template->category->name }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="d-flex">
                                    <a href="{{ route('dashboard.admin.creative-suite.templates.index') }}" class="btn btn-link">{{ __('Cancel') }}</a>
                                    <button type="submit" class="btn btn-primary ms-auto">{{ __('Update Template') }}</button>
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
