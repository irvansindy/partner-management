@extends('adminlte::page')

@section('title', 'Import Data Perusahaan')

@section('content_header')
    <h1>
        <i class="fas fa-file-import"></i> Import Data Perusahaan
    </h1>
@stop

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-file-import"></i> Import Data Perusahaan
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Silakan upload file Excel (.xlsx) sesuai template untuk import data perusahaan.
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h5><i class="fas fa-exclamation-triangle"></i> Terjadi Error:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <!-- ========================================
                         ACTION BUTTONS - INI YANG PENTING!
                         ======================================== -->
                    <div class="row mb-4">
                        <!-- Button Download Template -->
                        <div class="col-md-4">
                            <a href="{{ route('admin.company.export.template') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fas fa-download"></i> Download Template Excel
                            </a>
                            <small class="text-muted d-block mt-2 text-center">
                                Template kosong untuk import data baru
                            </small>
                        </div>

                        <!-- Button Export All -->
                        <div class="col-md-4">
                            <a href="{{ route('admin.company.export.all') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fas fa-file-export"></i> Export Semua Data
                            </a>
                            <small class="text-muted d-block mt-2 text-center">
                                Export semua data existing
                            </small>
                        </div>

                        <!-- Button Export Custom -->
                        <div class="col-md-4">
                            <a href="{{ route('admin.company.export.form') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-tasks"></i> Export Custom
                            </a>
                            <small class="text-muted d-block mt-2 text-center">
                                Pilih field yang ingin di-export
                            </small>
                        </div>
                    </div>

                    <hr>

                    <!-- Upload Form -->
                    <form action="{{ route('admin.company.import.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="file">
                                <i class="fas fa-file-excel"></i> Upload File Excel
                            </label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="file" required accept=".xlsx,.xls">
                                <label class="custom-file-label" for="file">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">
                                Format: .xlsx atau .xls | Max: 50MB
                            </small>
                        </div>

                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fas fa-search"></i> Preview Data
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-question-circle"></i> Petunjuk Import</h3>
                </div>
                <div class="card-body">
                    <ol>
                        <li>
                            <strong>Download Template</strong> - Klik tombol
                            <span class="badge badge-info">
                                <i class="fas fa-download"></i> Download Template Excel
                            </span>
                            untuk mendapatkan format yang benar
                        </li>
                        <li><strong>Baca Petunjuk</strong> - Buka sheet "📋 PETUNJUK" di file template</li>
                        <li><strong>Lihat Contoh</strong> - Lihat contoh data di row 2, lalu hapus sebelum isi data real</li>
                        <li><strong>Isi Data</strong> - Lengkapi data perusahaan sesuai kolom yang tersedia</li>
                        <li>
                            <strong>Multiple Values</strong> - Untuk address & bank yang lebih dari 1, gunakan separator
                            <code>|</code> (pipe)
                            <br>
                            Contoh: <code>Bank BCA | Bank Mandiri</code>
                        </li>
                        <li><strong>Upload File</strong> - Pilih file yang sudah diisi dan klik "Preview Data"</li>
                        <li><strong>Validasi</strong> - Review data di halaman preview sebelum import final</li>
                        <li><strong>Import</strong> - Klik "Import Data" untuk menyimpan ke database</li>
                    </ol>

                    <div class="alert alert-warning mt-3">
                        <h5><i class="fas fa-exclamation-triangle"></i> Perhatian:</h5>
                        <ul class="mb-0">
                            <li>Kolom <strong class="text-danger">nama_perusahaan</strong> wajib diisi (header berwarna merah)</li>
                            <li>Format email harus valid (contoh: email@domain.com)</li>
                            <li>Tahun berdiri harus berupa angka (contoh: 2020)</li>
                            <li>Jenis perusahaan hanya boleh: <code>vendor</code> atau <code>customer</code></li>
                            <li>Pastikan hanya sheet pertama yang berisi data</li>
                        </ul>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h5><i class="fas fa-lightbulb"></i> Tips:</h5>
                        <ul class="mb-0">
                            <li>Hover mouse di header kolom template untuk melihat catatan detail</li>
                            <li>Kolom dengan header tosca bisa diisi multiple data dengan separator <code>|</code></li>
                            <li>Gunakan fitur dropdown di kolom "jenis_perusahaan_vendorcustomer"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    // Show filename when file selected
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
</script>
@endsection