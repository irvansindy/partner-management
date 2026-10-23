@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h2>Import Kode Pos Indonesia</h2>
    <p>CSV UTF-8 maksimal 10 MB dengan header: <code>province,regency,district,village,postal_code</code>.</p>
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    @if (session('postal_import_result'))
        @php($result = session('postal_import_result'))
        <div class="alert alert-success">Ditambahkan: {{ $result['inserted'] }}, sudah ada: {{ $result['existing'] }}, ditolak: {{ $result['rejected'] }}.</div>
        @if ($result['rejected'])<a class="btn btn-outline-danger" href="{{ route('admin.postal-codes.import.errors') }}">Unduh CSV error</a>@endif
    @endif
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.postal-codes.import.store') }}" class="card mt-3">
        @csrf
        <div class="card-body"><label for="csv">File CSV</label><input class="form-control" id="csv" name="csv" type="file" accept=".csv,text/csv" required></div>
        <div class="card-footer"><button class="btn btn-primary" type="submit">Import kode pos</button></div>
    </form>
</div>
@endsection
