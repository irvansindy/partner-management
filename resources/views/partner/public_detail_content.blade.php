@php
    $fields = [
        'Jenis Perusahaan' => ucfirst($company->type), 'Nama Perusahaan' => $company->name,
        'Nama Grup Perusahaan' => $company->group_name, 'Tahun Berdiri' => $company->established_year,
        'Jumlah Karyawan' => $company->total_employee, 'Klasifikasi Bisnis' => $company->business_classification,
        'Detail Klasifikasi Bisnis' => $company->business_classification_detail,
        'NPWP' => $company->npwp, 'Alamat Situs Web' => $company->website_address,
        'Jenis Sertifikat' => $company->system_management, 'Email Koresponden' => $company->email_address,
        'Credit Limit' => $company->credit_limit, 'Term of Payment' => $company->term_of_payment,
    ];
    $sections = [
        ['Penanggung Jawab', $company->liablePeople, ['name' => 'Nama', 'position' => 'Jabatan', 'nik' => 'NIK']],
        ['Narahubung', $company->contact, ['department' => 'Departemen', 'position' => 'Jabatan', 'name' => 'Nama', 'email' => 'Email', 'telephone' => 'Telepon']],
        ['Alamat', $company->address, ['address' => 'Alamat', 'country' => 'Negara', 'province' => 'Provinsi', 'city' => 'Kota', 'zip_code' => 'Kode Pos', 'telephone' => 'Telepon', 'fax' => 'Fax', 'latitude' => 'Latitude', 'longitude' => 'Longitude']],
        ['Informasi Bank', $company->bank, ['name' => 'Bank', 'account_name' => 'Nama Rekening', 'account_number' => 'Nomor Rekening']],
    ];
@endphp
<section class="card">
    <div class="card-header"><h5 class="mb-0">Informasi Perusahaan</h5></div>
    <div class="card-body"><dl class="row mb-0">
        @foreach ($fields as $label => $value)
            <div class="col-md-6 mb-3"><dt class="mb-1">{{ $label }}</dt><dd class="mb-0" style="overflow-wrap:anywhere">{{ $value ?: '-' }}</dd></div>
        @endforeach
    </dl></div>
</section>
@foreach ($sections as [$title, $records, $columns])
    <section class="card">
        <div class="card-header"><h5 class="mb-0">{{ $title }}</h5></div>
        <div class="card-body">
            @forelse ($records as $record)
                <dl class="row mb-3 pb-2 border-bottom">
                    @foreach ($columns as $field => $label)
                        <div class="col-md-6 mb-3"><dt class="mb-1">{{ $label }}</dt><dd class="mb-0" style="overflow-wrap:anywhere">{{ $record->$field ?: '-' }}</dd></div>
                    @endforeach
                </dl>
            @empty
                <p class="text-muted mb-0">Belum ada data.</p>
            @endforelse
        </div>
    </section>
@endforeach
<section class="card">
    <div class="card-header"><h5 class="mb-0">Lampiran Berkas</h5></div>
    <div class="card-body"><div class="row">
        @forelse ($company->attachment as $file)
            <div class="col-md-4 mb-3"><div class="border rounded p-3 h-100 d-flex flex-column">
                <p style="overflow-wrap:anywhere">{{ $file->original_name ?? $file->filename }}</p>
                <a class="btn btn-outline-primary mt-auto" href="{{ route('admin.company-attachments.download', $file) }}">Download</a>
            </div></div>
        @empty
            <div class="col-12 text-muted">Belum ada lampiran.</div>
        @endforelse
    </div></div>
</section>
