@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h2>{{ __('data_tools.Import Kode Pos Indonesia') }}</h2>
    <p>{{ __('data_tools.postal_format') }} <code>province,regency,district,village,postal_code</code>.</p>
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    @if (session('postal_import_result'))
        @php($result = session('postal_import_result'))
        <div class="alert alert-{{ $result['rejected'] ? 'warning' : 'success' }}">{{ __('data_tools.postal_result', ['inserted' => $result['inserted'], 'existing' => $result['existing'], 'rejected' => $result['rejected']]) }}</div>
        @if ($result['rejected'])<a class="btn btn-outline-danger" href="{{ route('admin.postal-codes.import.errors') }}">{{ __('data_tools.Unduh CSV error') }}</a>@endif
    @endif
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.postal-codes.import.store') }}" class="card mt-3">
        @csrf
        <div class="card-body"><label for="csv">{{ __('data_tools.File CSV') }}</label><input class="form-control" id="csv" name="csv" type="file" accept=".csv,text/csv" required></div>
        <div class="card-footer"><button class="btn btn-primary" type="submit">{{ __('data_tools.Import kode pos') }}</button></div>
    </form>
</div>
@endsection
