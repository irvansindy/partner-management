@extends('layouts.main')

@section('content')
    @push('styles')
        @include('admin.company._alpha_styles')
    @endpush

    <div class="container-fluid company-tools-page">
        <div class="page-heading">
            <div>
                <h2>{{ __('tools.Export Data Perusahaan') }}</h2>
                <p>{{ __('tools.Pilih field yang dibutuhkan untuk menghasilkan file Excel atau PDF.') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons align-middle mr-1">fact_check</i> {{ __('tools.Pilih Field untuk Export') }}
                        </h3>
                    </div>
                    <form action="{{ route('admin.company.export.custom') }}" method="POST" id="exportForm">
                        @csrf

                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                {{ __('tools.Pilih kolom yang ingin Anda export ke Excel. Minimal pilih 1 kolom.') }}
                            </div>

                            <!-- Buttons untuk Select All / Deselect All -->
                            <div class="selection-toolbar mb-3">
                                <button type="button" class="btn btn-sm btn-primary" id="selectAll">
                                    <i class="fas fa-check-double"></i> {{ __('tools.Pilih Semua') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="deselectAll">
                                    <i class="fas fa-times"></i> {{ __('data_tools.Hapus Semua Pilihan') }}
                                </button>
                                <span class="selection-count" id="selectedCount">{{ __('data_tools.selected_count', ['count' => 0]) }}</span>
                            </div>

                            <hr>

                            <div class="row">
                                <!-- Company Information -->
                                <div class="col-md-6">
                                    <div class="card field-group">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i class="fas fa-building"></i> {{ __('data_tools.Informasi Perusahaan') }}
                                            </h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool btn-sm toggle-section"
                                                    data-section="company">
                                                    <i class="fas fa-check-square"></i> {{ __('data_tools.Toggle All') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="name" id="field_name" checked>
                                                <label class="form-check-label" for="field_name">
                                                    {{ __('tools.Nama Perusahaan') }} <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="group_name" id="field_group_name">
                                                <label class="form-check-label" for="field_group_name">
                                                    {{ __('data_tools.Group Perusahaan') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="type" id="field_type">
                                                <label class="form-check-label" for="field_type">
                                                    {{ __('tools.Jenis (Vendor/Customer)') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="established_year" id="field_established_year">
                                                <label class="form-check-label" for="field_established_year">
                                                    {{ __('tools.Tahun Berdiri') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="total_employee" id="field_total_employee">
                                                <label class="form-check-label" for="field_total_employee">
                                                    {{ __('tools.Jumlah Karyawan') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="business_classification"
                                                    id="field_business_classification">
                                                <label class="form-check-label" for="field_business_classification">
                                                    {{ __('tools.Klasifikasi Bisnis') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="business_classification_detail"
                                                    id="field_business_classification_detail">
                                                <label class="form-check-label" for="field_business_classification_detail">
                                                    {{ __('tools.Detail Bisnis') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field" type="checkbox"
                                                    name="fields[]" value="other_business" id="field_other_business">
                                                <label class="form-check-label" for="field_other_business">
                                                    {{ __('tools.Bisnis Lainnya') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field"
                                                    type="checkbox" name="fields[]" value="npwp" id="field_npwp">
                                                <label class="form-check-label" for="field_npwp">
                                                    NPWP
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field"
                                                    type="checkbox" name="fields[]" value="website_address"
                                                    id="field_website_address">
                                                <label class="form-check-label" for="field_website_address">
                                                    {{ __('partner.Website') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field"
                                                    type="checkbox" name="fields[]" value="system_management"
                                                    id="field_system_management">
                                                <label class="form-check-label" for="field_system_management">
                                                    {{ __('tools.Jenis Sertifikat') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field"
                                                    type="checkbox" name="fields[]" value="email_address"
                                                    id="field_email_address">
                                                <label class="form-check-label" for="field_email_address">
                                                    {{ __('tools.Email Koresponden') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field"
                                                    type="checkbox" name="fields[]" value="credit_limit"
                                                    id="field_credit_limit">
                                                <label class="form-check-label" for="field_credit_limit">
                                                    {{ __('tools.Batas Kredit') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox company-field"
                                                    type="checkbox" name="fields[]" value="term_of_payment"
                                                    id="field_term_of_payment">
                                                <label class="form-check-label" for="field_term_of_payment">
                                                    {{ __('data_tools.Jangka Waktu Pembayaran') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Person -->
                                <div class="col-md-6">
                                    <div class="card field-group">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i class="fas fa-user-tie"></i> {{ __('data_tools.Contact Person') }}
                                            </h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool btn-sm toggle-section"
                                                    data-section="contact">
                                                    <i class="fas fa-check-square"></i> {{ __('data_tools.Toggle All') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox contact-field"
                                                    type="checkbox" name="fields[]" value="contact_name"
                                                    id="field_contact_name">
                                                <label class="form-check-label" for="field_contact_name">
                                                    {{ __('tools.Nama Kontak') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox contact-field"
                                                    type="checkbox" name="fields[]" value="contact_position"
                                                    id="field_contact_position">
                                                <label class="form-check-label" for="field_contact_position">
                                                    {{ __('tools.Jabatan Kontak') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox contact-field"
                                                    type="checkbox" name="fields[]" value="contact_department"
                                                    id="field_contact_department">
                                                <label class="form-check-label" for="field_contact_department">
                                                    {{ __('tools.Departemen Kontak') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox contact-field"
                                                    type="checkbox" name="fields[]" value="contact_email"
                                                    id="field_contact_email">
                                                <label class="form-check-label" for="field_contact_email">
                                                    {{ __('tools.Email Kontak') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox contact-field"
                                                    type="checkbox" name="fields[]" value="contact_telephone"
                                                    id="field_contact_telephone">
                                                <label class="form-check-label" for="field_contact_telephone">
                                                    {{ __('tools.Telepon Kontak') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Addresses -->
                                    <div class="card field-group">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i class="fas fa-map-marker-alt"></i> {{ __('data_tools.Alamat') }}
                                            </h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool btn-sm toggle-section"
                                                    data-section="address">
                                                    <i class="fas fa-check-square"></i> {{ __('data_tools.Toggle All') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox address-field"
                                                    type="checkbox" name="fields[]" value="address" id="field_address">
                                                <label class="form-check-label" for="field_address">
                                                    {{ __('data_tools.Alamat Lengkap') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox address-field"
                                                    type="checkbox" name="fields[]" value="zip_code"
                                                    id="field_zip_code">
                                                <label class="form-check-label" for="field_zip_code">
                                                    {{ __('data_tools.Kode Pos') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox address-field"
                                                    type="checkbox" name="fields[]" value="telephone_address"
                                                    id="field_telephone_address">
                                                <label class="form-check-label" for="field_telephone_address">
                                                    {{ __('data_tools.Telepon Alamat') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox address-field"
                                                    type="checkbox" name="fields[]" value="fax" id="field_fax">
                                                <label class="form-check-label" for="field_fax">
                                                    Fax
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Banks -->
                                <div class="col-md-6">
                                    <div class="card field-group">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i class="fas fa-university"></i> {{ __('data_tools.Bank') }}
                                            </h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool btn-sm toggle-section"
                                                    data-section="bank">
                                                    <i class="fas fa-check-square"></i> {{ __('data_tools.Toggle All') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox bank-field" type="checkbox"
                                                    name="fields[]" value="bank_name" id="field_bank_name">
                                                <label class="form-check-label" for="field_bank_name">
                                                    {{ __('tools.Nama Bank') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox bank-field" type="checkbox"
                                                    name="fields[]" value="account_name" id="field_account_name">
                                                <label class="form-check-label" for="field_account_name">
                                                    {{ __('tools.Nama Akun') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox bank-field" type="checkbox"
                                                    name="fields[]" value="account_number" id="field_account_number">
                                                <label class="form-check-label" for="field_account_number">
                                                    {{ __('tools.Nomor Akun') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Liable People -->
                                <div class="col-md-6">
                                    <div class="card field-group">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i class="fas fa-user-shield"></i> {{ __('data_tools.Penanggung Jawab') }}
                                            </h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool btn-sm toggle-section"
                                                    data-section="liable">
                                                    <i class="fas fa-check-square"></i> {{ __('data_tools.Toggle All') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox liable-field"
                                                    type="checkbox" name="fields[]" value="liable_name"
                                                    id="field_liable_name">
                                                <label class="form-check-label" for="field_liable_name">
                                                    {{ __('tools.Nama Penanggung Jawab') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox liable-field"
                                                    type="checkbox" name="fields[]" value="liable_nik"
                                                    id="field_liable_nik">
                                                <label class="form-check-label" for="field_liable_nik">
                                                    {{ __('tools.NIK Penanggung Jawab') }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input field-checkbox liable-field"
                                                    type="checkbox" name="fields[]" value="liable_position"
                                                    id="field_liable_position">
                                                <label class="form-check-label" for="field_liable_position">
                                                    {{ __('tools.Jabatan Penanggung Jawab') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="mt-3 d-flex flex-wrap gap-3">
                                <button type="submit"
                                        formaction="{{ route('admin.company.export.custom') }}"
                                        class="btn btn-success btn-sm mx-1"
                                        id="btnExportExcel">
                                    <i class="fas fa-file-download me-1"></i> {{ __('tools.Export Excel') }}
                                </button>

                                <button type="submit"
                                        formaction="{{ route('admin.company.export.custom.pdf') }}"
                                        class="btn btn-danger btn-sm mx-1"
                                        id="btnExportPdf">
                                    <i class="fas fa-file-pdf me-1"></i> {{ __('tools.Export PDF') }}
                                </button>

                                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm mx-1">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('tools.Kembali') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Update counter
            function updateCounter() {
                var count = $('.field-checkbox:checked').length;
                $('#selectedCount').text(@json(__('data_tools.selected_count')).replace(':count', count));
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

            let exportSubmitter = null;
            $('#btnExportExcel, #btnExportPdf').on('click', function () { exportSubmitter = this; });
            $('#exportForm').on('submit', async function (event) {
                event.preventDefault();
                if (!$('.field-checkbox:checked').length) {
                    Swal.fire({ icon: 'warning', title: @json(__('data_tools.Pilih Minimal 1 Field')), text: @json(__('data_tools.Anda harus memilih minimal 1 field untuk di-export!')) });
                    return;
                }
                const button = event.originalEvent && event.originalEvent.submitter || exportSubmitter || document.getElementById('btnExportExcel');
                const originalHtml = button.innerHTML;
                const buttons = $('#btnExportExcel, #btnExportPdf');
                buttons.prop('disabled', true);
                button.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> ' + @json(__('data_tools.Memproses...'));
                let failureMessage = @json(__('data_tools.export_failed'));
                try {
                    const response = await fetch(button.formAction || this.action, {
                        method: 'POST', body: new FormData(this), credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const contentType = response.headers.get('Content-Type') || '';
                    if (!response.ok || !/application\/(?:pdf|vnd\.|octet-stream)/i.test(contentType)) {
                        let message = @json(__('data_tools.export_failed'));
                        if (contentType.includes('application/json')) {
                            const error = await response.json();
                            if (error.errors) message = Object.values(error.errors).flat().join('\n');
                        }
                        failureMessage = message;
                        throw new Error(message);
                    }
                    const blob = await response.blob();
                    const disposition = response.headers.get('Content-Disposition') || '';
                    const filename = /filename="([^"]+)"/i.exec(disposition);
                    const link = document.createElement('a');
                    const blobUrl = URL.createObjectURL(blob);
                    link.href = blobUrl;
                    link.download = filename ? filename[1] : (contentType.includes('pdf') ? 'company-export.pdf' : 'company-export.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    setTimeout(function () { URL.revokeObjectURL(blobUrl); }, 1000);
                    Swal.fire({ icon: 'success', title: @json(__('data_tools.export_ready')), text: @json(__('data_tools.export_done')) });
                } catch (error) {
                    Swal.fire({ icon: 'error', title: @json(__('settings.Error')), text: failureMessage });
                } finally {
                    button.innerHTML = originalHtml;
                    buttons.prop('disabled', false);
                }
            });

            // Initial counter
            updateCounter();
        });
    </script>
@endpush
