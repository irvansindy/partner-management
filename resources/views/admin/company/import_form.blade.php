@extends('layouts.main')

@section('content')
@push('styles')
    @include('admin.company._alpha_styles')
    <style>
        .import-instructions-modal .modal-header {
            align-items: center;
            border-bottom-color: #e6e9ee;
            padding: 18px 24px;
        }

        .import-instructions-modal .modal-title {
            color: #252a34;
            font-size: 18px;
            font-weight: 600;
        }

        .import-instructions-modal .modal-body {
            color: #3f4652;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.55;
            padding: 22px 28px;
        }

        .import-instructions-modal .instruction-list {
            margin: 0;
            padding-left: 22px;
        }

        .import-instructions-modal .instruction-list li {
            margin-bottom: 8px;
            padding-left: 3px;
        }

        .import-instructions-modal .instruction-list strong {
            color: #2f3540;
            font-weight: 600;
        }

        .import-instructions-modal .instruction-action-example {
            background: #f0f2f5;
            border: 1px solid #d8dde5;
            border-radius: 4px;
            color: #445064;
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 7px;
        }

        .import-instructions-modal .import-guidance {
            border: 1px solid #dde2e8;
            border-left-width: 4px;
            border-radius: 6px;
            box-shadow: none;
            color: #424a57;
            margin: 18px 0 0;
            padding: 15px 18px;
        }

        .import-instructions-modal .import-guidance h5 {
            color: #303744;
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 8px;
        }

        .import-instructions-modal .import-guidance ul {
            margin: 0;
            padding-left: 20px;
        }

        .import-instructions-modal .import-guidance--warning {
            background: #fffaf0;
            border-left-color: #c99124;
        }

        .import-instructions-modal .import-guidance--tip {
            background: #f4f8fa;
            border-left-color: #477f9b;
        }

        .import-instructions-modal .modal-footer {
            border-top-color: #e6e9ee;
            padding: 14px 24px;
        }
    </style>
@endpush

<div class="container-fluid company-tools-page">
    <div class="page-heading">
        <div>
            <h2>Import Data Perusahaan</h2>
            <p>Upload file Excel sesuai template untuk menambahkan data perusahaan.</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                data-target="#importInstructionsModal" aria-controls="importInstructionsModal"
                aria-haspopup="dialog">
                <i class="fas fa-question-circle mr-1" aria-hidden="true"></i> Petunjuk Import
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons align-middle mr-1">upload_file</i> Import Data Perusahaan
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
                    <div class="upload-actions mb-4">
                        <!-- Button Download Template -->
                        <div>
                            <a href="{{ route('admin.company.export.template') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fas fa-download"></i> Download Template Excel
                            </a>
                            <small class="text-muted d-block mt-2 text-center">
                                Template kosong untuk import data baru
                            </small>
                        </div>

                        <!-- Button Export All -->
                        <div>
                            <a href="{{ route('admin.company.export.all') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fas fa-file-export"></i> Export Semua Data
                            </a>
                            <small class="text-muted d-block mt-2 text-center">
                                Export semua data existing
                            </small>
                        </div>

                        <!-- Button Export Custom -->
                        <div>
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

    <div class="modal fade import-instructions-modal" id="importInstructionsModal" tabindex="-1" role="dialog"
        aria-labelledby="importInstructionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="importInstructionsModalLabel">
                        <i class="fas fa-question-circle mr-1" aria-hidden="true"></i> Petunjuk Import
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup petunjuk import">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ol class="instruction-list">
                        <li>
                            <strong>Download Template</strong> - Klik tombol
                            <span class="instruction-action-example">
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

                    <div class="import-guidance import-guidance--warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> Perhatian:</h5>
                        <ul class="mb-0">
                            <li>Kolom <strong class="text-danger">nama_perusahaan</strong> wajib diisi (header berwarna merah)</li>
                            <li>Format email harus valid (contoh: email@domain.com)</li>
                            <li>Tahun berdiri harus berupa angka (contoh: 2020)</li>
                            <li>Jenis perusahaan hanya boleh: <code>vendor</code> atau <code>customer</code></li>
                            <li>Pastikan hanya sheet pertama yang berisi data</li>
                        </ul>
                    </div>

                    <div class="import-guidance import-guidance--tip">
                        <h5><i class="fas fa-lightbulb"></i> Tips:</h5>
                        <ul class="mb-0">
                            <li>Hover mouse di header kolom template untuk melihat catatan detail</li>
                            <li>Kolom dengan header tosca bisa diisi multiple data dengan separator <code>|</code></li>
                            <li>Gunakan fitur dropdown di kolom "jenis_perusahaan_vendorcustomer"</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Show filename when file selected
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
</script>
@endpush
