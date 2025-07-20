@extends('panel.layout.app')
@section('title', __('Creative Suite Templates'))

@section('content')
    <div class="page-header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Admin') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Creative Suite Templates') }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('dashboard.admin.creative-suite.templates.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            {{ __('Add Template') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Templates') }}</h3>
                            <div class="card-actions">
                                <form method="GET" action="{{ route('dashboard.admin.creative-suite.templates.index') }}" class="d-flex gap-2">
                                    <select name="category" class="form-select" onchange="this.form.submit()">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="{{ __('Search templates...') }}" value="{{ $search }}">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="10" cy="10" r="7"/>
                                                <path d="m21 21-6-6"/>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($templates->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Preview') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Created') }}</th>
                                                <th class="w-1">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($templates as $template)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $previewUrl = $template->preview_image;
                                                            // Fix localhost URLs to use the correct server address
                                                            if (str_starts_with($previewUrl, 'localhost/')) {
                                                                $previewUrl = request()->getSchemeAndHttpHost() . '/' . substr($previewUrl, 10);
                                                            } elseif (str_starts_with($previewUrl, 'http://localhost/')) {
                                                                $previewUrl = str_replace('http://localhost/', request()->getSchemeAndHttpHost() . '/', $previewUrl);
                                                            }
                                                        @endphp
                                                        <div class="avatar avatar-lg" style="background-image: url('{{ $previewUrl }}')"></div>
                                                    </td>
                                                    <td>
                                                        <div class="text-reset">{{ $template->name }}</div>
                                                    </td>
                                                    <td>
                                                        @if($template->category)
                                                            <span class="badge bg-{{ $template->category->color ?? 'primary' }}">
                                                                {{ $template->category->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">{{ __('No Category') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="text-muted">
                                                            {{ $template->created_at->format('M d, Y') }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-list flex-nowrap">
                                                            <a href="{{ route('dashboard.admin.creative-suite.templates.edit', $template) }}" class="btn btn-sm btn-outline-primary">
                                                                {{ __('Edit') }}
                                                            </a>
                                                            <form method="POST" action="{{ route('dashboard.admin.creative-suite.templates.duplicate', $template) }}" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                                    {{ __('Duplicate') }}
                                                                </button>
                                                            </form>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTemplate({{ $template->id }})">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    {{ $templates->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="empty">
                                    <div class="empty-img">
                                        <img src="{{ asset('assets/img/empty.svg') }}" height="128" alt="">
                                    </div>
                                    <p class="empty-title">{{ __('No templates found') }}</p>
                                    <p class="empty-subtitle text-muted">
                                        {{ __('Try adjusting your search or filter to find what you\'re looking for.') }}
                                    </p>
                                    <div class="empty-action">
                                        <a href="{{ route('dashboard.admin.creative-suite.templates.create') }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <line x1="12" y1="5" x2="12" y2="19"/>
                                                <line x1="5" y1="12" x2="19" y2="12"/>
                                            </svg>
                                            {{ __('Add your first template') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteTemplate(templateId) {
            if (confirm('{{ __("Are you sure you want to delete this template?") }}')) {
                fetch(`/admin/creative-suite/templates/${templateId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert(data.message || '{{ __("Error deleting template") }}');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __("Error deleting template") }}');
                });
            }
        }
    </script>
@endsection
