@extends('adminlte::page')

@section('title', 'Form Submissions')

@section('content_header')
    <h1>Form Submissions: {{ $formLink->title }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.form-links.show', $formLink) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Form Details
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="submissions-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Submitted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $company->type === 'vendor' ? 'info' : 'success' }}">
                                        {{ ucfirst($company->type) }}
                                    </span>
                                </td>
                                <td>{{ $company->email_address }}</td>
                                <td>
                                    @if ($company->contact->isNotEmpty())
                                        {{ $company->contact->first()->telephone }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $company->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No submissions yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#submissions-table').DataTable({
                "paging": false,
                "searching": true,
                "ordering": true,
                "info": false
            });
        });
    </script>
@stop
