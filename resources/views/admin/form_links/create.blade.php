@extends('adminlte::page')

@section('title', 'Create Form Link')

@section('content_header')
    <h1>Create New Form Link</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Link Details</h3>
                    </div>
                    <form action="{{ route('admin.form-links.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="form_type">Form Type <span class="text-danger">*</span></label>
                                <select name="form_type" id="form_type"
                                    class="form-control @error('form_type') is-invalid @enderror" required>
                                    <option value="">-- Select Form Type --</option>
                                    <option value="vendor" {{ old('form_type') === 'vendor' ? 'selected' : '' }}>Vendor
                                    </option>
                                    <option value="customer" {{ old('form_type') === 'customer' ? 'selected' : '' }}>
                                        Customer</option>
                                </select>
                                @error('form_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- kondisi super-admin yang membuat --}}
                            @if (auth()->user()->roles[0]->name == 'super-admin')
                            <div class="form-group">
                                <label for="user_form">For User</label>
                                <select class="form-control" name="user_form" id="user_form">
                                    @forelse ($pic as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        <option value="">Tidak ada PIC</option>
                                    @endforelse
                                </select>
                            </div>

                            @endif

                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    placeholder="e.g., Vendor Registration Form Q1 2025" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="Optional description for internal use">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="expires_at">Expiration Date (Optional)</label>
                                <input type="datetime-local" name="expires_at" id="expires_at"
                                    class="form-control @error('expires_at') is-invalid @enderror"
                                    value="{{ old('expires_at') }}">
                                <small class="form-text text-muted">Leave empty for no expiration</small>
                                @error('expires_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="max_submissions">Maximum Submissions (Optional)</label>
                                <input type="number" name="max_submissions" id="max_submissions"
                                    class="form-control @error('max_submissions') is-invalid @enderror"
                                    value="{{ old('max_submissions') }}" min="1"
                                    placeholder="Leave empty for unlimited">
                                <small class="form-text text-muted">Leave empty for unlimited submissions</small>
                                @error('max_submissions')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Form Link
                            </button>
                            <a href="{{ route('admin.form-links.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
