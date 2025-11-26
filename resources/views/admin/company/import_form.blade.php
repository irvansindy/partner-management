@extends('adminlte::page')

@section('content')

    <div class="container">
        <h3>Import Data Perusahaan</h3>
        <p>Silakan upload file Excel (.xlsx) sesuai template.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.company.import.preview') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group my-3">
                <label for="file">Upload File Excel</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <button class="btn btn-primary mt-3" type="submit">
                Preview Data
            </button>
        </form>
    </div>

@endsection
