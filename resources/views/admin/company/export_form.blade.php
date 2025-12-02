@extends('adminlte::page')

@section('title', 'Export Data Perusahaan')

@section('content_header')
    <h1>
        <i class="fas fa-file-export"></i> Export Data Perusahaan
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">
                        <i class="fas fa-check-square"></i> Pilih Field untuk Export
                    </h3>
                </div>
                <form action="{{ route('admin.company.export.custom') }}" method="POST" id="exportForm">
                    @csrf

                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Pilih kolom yang ingin Anda export ke Excel. Minimal pilih 1 kolom.
                        </div>

                        <!-- Buttons untuk Select All / Deselect All -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" id="selectAll">
                                <i class="fas fa-check-double"></i> Pilih Semua
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="deselectAll">
                                <i class="fas fa-times"></i> Hapus Semua Pilihan
                            </button>
                            <span class="ml-3 badge badge-info" id="selectedCount">0 field dipilih</span>
                        </div>

                        <hr>

                        <div class="row">
                            <!-- Company Information -->
                            <div class="col-md-6">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-building"></i> Informasi Perusahaan
                                        </h5>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool btn-sm toggle-section" data-section="company">
                                                <i class="fas fa-check-square"></i> Toggle All
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="name" id="field_name" checked>
                                            <label class="form-check-label" for="field_name">
                                                Nama Perusahaan <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="group_name" id="field_group_name">
                                            <label class="form-check-label" for="field_group_name">
                                                Group Perusahaan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="type" id="field_type">
                                            <label class="form-check-label" for="field_type">
                                                Jenis (Vendor/Customer)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="established_year" id="field_established_year">
                                            <label class="form-check-label" for="field_established_year">
                                                Tahun Berdiri
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="total_employee" id="field_total_employee">
                                            <label class="form-check-label" for="field_total_employee">
                                                Jumlah Karyawan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="business_classification" id="field_business_classification">
                                            <label class="form-check-label" for="field_business_classification">
                                                Klasifikasi Bisnis
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="business_classification_detail" id="field_business_classification_detail">
                                            <label class="form-check-label" for="field_business_classification_detail">
                                                Detail Bisnis
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="other_business" id="field_other_business">
                                            <label class="form-check-label" for="field_other_business">
                                                Bisnis Lainnya
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="npwp" id="field_npwp">
                                            <label class="form-check-label" for="field_npwp">
                                                NPWP
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="website_address" id="field_website_address">
                                            <label class="form-check-label" for="field_website_address">
                                                Website
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="system_management" id="field_system_management">
                                            <label class="form-check-label" for="field_system_management">
                                                Jenis Sertifikat
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="email_address" id="field_email_address">
                                            <label class="form-check-label" for="field_email_address">
                                                Email Koresponden
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="credit_limit" id="field_credit_limit">
                                            <label class="form-check-label" for="field_credit_limit">
                                                Batas Kredit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox company-field" type="checkbox" name="fields[]" value="term_of_payment" id="field_term_of_payment">
                                            <label class="form-check-label" for="field_term_of_payment">
                                                Jangka Waktu Pembayaran
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Person -->
                            <div class="col-md-6">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-user-tie"></i> Contact Person
                                        </h5>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool btn-sm toggle-section" data-section="contact">
                                                <i class="fas fa-check-square"></i> Toggle All
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox contact-field" type="checkbox" name="fields[]" value="contact_name" id="field_contact_name">
                                            <label class="form-check-label" for="field_contact_name">
                                                Nama Kontak
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox contact-field" type="checkbox" name="fields[]" value="contact_position" id="field_contact_position">
                                            <label class="form-check-label" for="field_contact_position">
                                                Jabatan Kontak
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox contact-field" type="checkbox" name="fields[]" value="contact_department" id="field_contact_department">
                                            <label class="form-check-label" for="field_contact_department">
                                                Departemen Kontak
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox contact-field" type="checkbox" name="fields[]" value="contact_email" id="field_contact_email">
                                            <label class="form-check-label" for="field_contact_email">
                                                Email Kontak
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox contact-field" type="checkbox" name="fields[]" value="contact_telephone" id="field_contact_telephone">
                                            <label class="form-check-label" for="field_contact_telephone">
                                                Telepon Kontak
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Addresses -->
                                <div class="card card-outline card-warning">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-map-marker-alt"></i> Alamat
                                        </h5>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool btn-sm toggle-section" data-section="address">
                                                <i class="fas fa-check-square"></i> Toggle All
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox address-field" type="checkbox" name="fields[]" value="address" id="field_address">
                                            <label class="form-check-label" for="field_address">
                                                Alamat Lengkap
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox address-field" type="checkbox" name="fields[]" value="zip_code" id="field_zip_code">
                                            <label class="form-check-label" for="field_zip_code">
                                                Kode Pos
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox address-field" type="checkbox" name="fields[]" value="telephone_address" id="field_telephone_address">
                                            <label class="form-check-label" for="field_telephone_address">
                                                Telepon Alamat
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox address-field" type="checkbox" name="fields[]" value="fax" id="field_fax">
                                            <label class="form-check-label" for="field_fax">
                                                Fax
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Banks -->
                            <div class="col-md-6">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-university"></i> Bank
                                        </h5>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool btn-sm toggle-section" data-section="bank">
                                                <i class="fas fa-check-square"></i> Toggle All
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox bank-field" type="checkbox" name="fields[]" value="bank_name" id="field_bank_name">
                                            <label class="form-check-label" for="field_bank_name">
                                                Nama Bank
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox bank-field" type="checkbox" name="fields[]" value="account_name" id="field_account_name">
                                            <label class="form-check-label" for="field_account_name">
                                                Nama Akun
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox bank-field" type="checkbox" name="fields[]" value="account_number" id="field_account_number">
                                            <label class="form-check-label" for="field_account_number">
                                                Nomor Akun
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Liable People -->
                            <div class="col-md-6">
                                <div class="card card-outline card-danger">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-user-shield"></i> Penanggung Jawab
                                        </h5>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool btn-sm toggle-section" data-section="liable">
                                                <i class="fas fa-check-square"></i> Toggle All
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox liable-field" type="checkbox" name="fields[]" value="liable_name" id="field_liable_name">
                                            <label class="form-check-label" for="field_liable_name">
                                                Nama Penanggung Jawab
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox liable-field" type="checkbox" name="fields[]" value="liable_nik" id="field_liable_nik">
                                            <label class="form-check-label" for="field_liable_nik">
                                                NIK Penanggung Jawab
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox liable-field" type="checkbox" name="fields[]" value="liable_position" id="field_liable_position">
                                            <label class="form-check-label" for="field_liable_position">
                                                Jabatan Penanggung Jawab
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-lg" id="btnExport">
                            <i class="fas fa-file-download"></i> Export ke Excel
                        </button>
                        <a href="{{ route('admin.company.import.form') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .form-check {
        margin-bottom: 10px;
        padding-left: 1.5rem;
    }
    .form-check-label {
        cursor: pointer;
        user-select: none;
    }
    .card-body {
        max-height: 400px;
        overflow-y: auto;
    }
    .toggle-section {
        padding: 2px 8px;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Update counter
    function updateCounter() {
        var count = $('.field-checkbox:checked').length;
        $('#selectedCount').text(count + ' field dipilih');
    }

    // Select All
    $('#selectAll').click(function() {
        $('.field-checkbox').prop('checked', true);
        updateCounter();
    });

    // Deselect All
    $('#deselectAll').click(function() {
        $('.field-checkbox').prop('checked', false);
        updateCounter();
    });

    // Toggle section
    $('.toggle-section').click(function() {
        var section = $(this).data('section');
        var checkboxes = $('.' + section + '-field');
        var allChecked = checkboxes.filter(':checked').length === checkboxes.length;

        checkboxes.prop('checked', !allChecked);
        updateCounter();
    });

    // Update counter on change
    $('.field-checkbox').change(function() {
        updateCounter();
    });

    // Validation before submit
    $('#exportForm').submit(function(e) {
        var checkedCount = $('.field-checkbox:checked').length;

        if (checkedCount === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Minimal 1 Field',
                text: 'Anda harus memilih minimal 1 field untuk di-export!',
            });
            return false;
        }

        // Show loading
        $('#btnExport').html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);
    });

    // Initial counter
    updateCounter();
});
</script>
@stop