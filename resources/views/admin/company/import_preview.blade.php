@extends('adminlte::page')

@section('content')

<div class="container">
    <h3>Preview Import</h3>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!empty($errorsList) && count($errorsList) > 0)
        <div class="alert alert-danger">
            <h5>Terjadi Error Validasi:</h5>
            <ul class="mb-0">
                @foreach ($errorsList as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        <a href="{{ route('admin.company.import.form') }}" class="btn btn-warning mt-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Pastikan data sudah benar. Jika sudah, klik tombol "Import Data".
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        @foreach ($rows->first() as $key => $value)
                            <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $index => $r)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @foreach ($r as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('admin.company.import.confirm') }}" method="POST" id="confirmForm">
            @csrf
            <button class="btn btn-success" type="submit">
                <i class="fas fa-check"></i> Import Data
            </button>
            <a href="{{ route('admin.company.import.form') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </form>
    @endif
</div>

@endsection

@section('js')
<script>
    document.getElementById('confirmForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    });
</script>
@endsection