@extends('adminlte::page')

@section('title', 'Edit Form Link')

@section('content_header')
    <h1>Edit Form Link</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Form Link Details</h3>
                    </div>
                    <form action="{{ route('admin.form-links.update', $formLink) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="form_type">Form Type</label>
                                <input type="text" class="form-control" value="{{ ucfirst($formLink->form_type) }}"
                                    disabled>
                                <small class="form-text text-muted">Form type cannot be changed</small>
                            </div>

                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $formLink->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $formLink->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="expires_at">Expiration Date (Optional)</label>
                                <input type="datetime-local" name="expires_at" id="expires_at"
                                    class="form-control @error('expires_at') is-invalid @enderror"
                                    value="{{ old('expires_at', $formLink->expires_at ? $formLink->expires_at->format('Y-m-d\TH:i') : '') }}">
                                @error('expires_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="max_submissions">Maximum Submissions (Optional)</label>
                                <input type="number" name="max_submissions" id="max_submissions"
                                    class="form-control @error('max_submissions') is-invalid @enderror"
                                    value="{{ old('max_submissions', $formLink->max_submissions) }}" min="1">
                                @error('max_submissions')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Form Link
                            </button>
                            <a href="{{ route('admin.form-links.show', $formLink) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
