{{-- <style>
    body {
        font-family: sans-serif;
        font-size: 9px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th {
        background-color: #4CAF50;
        color: white;
        padding: 5px;
        text-align: left;
        border: 1px solid #ddd;
    }

    td {
        padding: 4px 5px;
        border: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    h3 {
        font-size: 12px;
        margin-bottom: 4px;
    }

    .sub {
        font-size: 8px;
        color: #555;
        margin-bottom: 10px;
    }
</style>

<h3>Data Export Perusahaan</h3>
<p class="sub">{{ __('data_tools.export_date', ['date' => now()->locale(app()->getLocale())->translatedFormat('d M Y H:i:s')]) }}</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            @foreach ($selectedFields as $field)
                <th>{{ ucwords(str_replace('_', ' ', $field)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($companies as $i => $company)
            <tr>
                <td>{{ $i + 1 }}</td>
                @foreach ($selectedFields as $field)
                    <td>{{ $company->{$field} ?? '-' }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($selectedFields) + 1 }}" style="text-align:center;">
                    Tidak ada data
                </td>
            </tr>
        @endforelse
    </tbody>
</table> --}}
@php
    // Label mapping: field value → label tampil di header
    $fieldLabels = [
        'name'                          => __('tools.Nama Perusahaan'),
        'group_name'                    => __('data_tools.Group Perusahaan'),
        'type'                          => __('data_tools.Jenis'),
        'established_year'              => __('tools.Tahun Berdiri'),
        'total_employee'                => __('tools.Jumlah Karyawan'),
        'business_classification'       => __('tools.Klasifikasi Bisnis'),
        'business_classification_detail'=> __('tools.Detail Bisnis'),
        'other_business'                => __('tools.Bisnis Lainnya'),
        'npwp'                          => 'NPWP',
        'website_address'               => __('partner.Website'),
        'system_management'             => __('tools.Jenis Sertifikat'),
        'email_address'                 => 'Email',
        'credit_limit'                  => __('tools.Batas Kredit'),
        'term_of_payment'               => __('data_tools.Jangka Waktu'),
        // Contact
        'contact_name'                  => __('tools.Nama Kontak'),
        'contact_position'              => __('tools.Jabatan Kontak'),
        'contact_department'            => __('data_tools.Dept. Kontak'),
        'contact_email'                 => __('tools.Email Kontak'),
        'contact_telephone'             => __('tools.Telepon Kontak'),
        // Address
        'address'                       => __('data_tools.Alamat'),
        'zip_code'                      => __('data_tools.Kode Pos'),
        'telephone_address'             => __('data_tools.Telepon Alamat'),
        'fax'                           => 'Fax',
        // Bank
        'bank_name'                     => __('tools.Nama Bank'),
        'account_name'                  => __('data_tools.Nama Akun'),
        'account_number'                => __('data_tools.Nomor Akun'),
        // Liable
        'liable_name'                   => __('tools.Nama Penanggung Jawab'),
        'liable_nik'                    => __('tools.NIK Penanggung Jawab'),
        'liable_position'               => __('tools.Jabatan Penanggung Jawab'),
    ];

    // Kelompokkan field berdasarkan sumber datanya
    $contactFields  = ['contact_name', 'contact_position', 'contact_department', 'contact_email', 'contact_telephone'];
    $addressFields  = ['address', 'zip_code', 'telephone_address', 'fax'];
    $bankFields     = ['bank_name', 'account_name', 'account_number'];
    $liableFields   = ['liable_name', 'liable_nik', 'liable_position'];

    // Map field relasi → key di model relasinya
    $contactMap = [
        'contact_name'       => 'name',
        'contact_position'   => 'position',
        'contact_department' => 'department',
        'contact_email'      => 'email',
        'contact_telephone'  => 'telephone',
    ];
    $addressMap = [
        'address'           => 'address',
        'zip_code'          => 'zip_code',
        'telephone_address' => 'telephone',
        'fax'               => 'fax',
    ];
    $bankMap = [
        'bank_name'      => 'name',
        'account_name'   => 'account_name',
        'account_number' => 'account_number',
    ];
    $liableMap = [
        'liable_name'     => 'name',
        'liable_nik'      => 'nik',
        'liable_position' => 'position',
    ];
@endphp

<style>
    body        { font-family: sans-serif; font-size: 8px; }
    h3          { font-size: 11px; margin-bottom: 2px; color: #2c3e50; }
    .sub        { font-size: 8px; color: #555; margin-bottom: 8px; }
    table       { width: 100%; border-collapse: collapse; margin-top: 6px; }
    th          { background-color: #27ae60; color: white; padding: 4px 5px;
                  text-align: center; border: 1px solid #aaa; white-space: nowrap; font-size: 7.5px; }
    td          { padding: 3px 5px; border: 1px solid #ccc; vertical-align: top; font-size: 7.5px; }
    tr:nth-child(even) td { background-color: #f5f5f5; }
    .no-data    { text-align: center; color: #888; font-style: italic; }
</style>

<h3>{{ __('data_tools.Data Export Perusahaan') }}</h3>
<p class="sub">{{ __('data_tools.export_date', ['date' => now()->locale(app()->getLocale())->translatedFormat('d M Y H:i:s')]) }}</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            @foreach ($selectedFields as $field)
                <th>{{ $fieldLabels[$field] ?? ucwords(str_replace('_', ' ', $field)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($companies as $i => $company)
            @php
                // ── Contact (ambil yang pertama) ──────────────────────
                $contact = $company->contact->first();

                // ── Address (gabung dengan | ) ────────────────────────
                $addressData = [
                    'address'           => $company->address->pluck('address')->filter()->implode(' | '),
                    'zip_code'          => $company->address->pluck('zip_code')->filter()->implode(' | '),
                    'telephone_address' => $company->address->pluck('telephone')->filter()->implode(' | '),
                    'fax'               => $company->address->pluck('fax')->filter()->implode(' | '),
                ];

                // ── Bank (gabung dengan | ) ───────────────────────────
                $bankData = [
                    'bank_name'      => $company->bank->pluck('name')->filter()->implode(' | '),
                    'account_name'   => $company->bank->pluck('account_name')->filter()->implode(' | '),
                    'account_number' => $company->bank->pluck('account_number')->filter()->implode(' | '),
                ];

                // ── Liable People (gabung dengan | ) ─────────────────
                $liableData = [
                    'liable_name'     => $company->liablePeople->pluck('name')->filter()->implode(' | '),
                    'liable_nik'      => $company->liablePeople->pluck('nik')->filter()->implode(' | '),
                    'liable_position' => $company->liablePeople->pluck('position')->filter()->implode(' | '),
                ];
            @endphp
            <tr>
                <td style="text-align:center;">{{ $i + 1 }}</td>
                @foreach ($selectedFields as $field)
                    <td>
                        @if (in_array($field, $contactFields))
                            {{ optional($contact)->{$contactMap[$field]} ?? '-' }}

                        @elseif (in_array($field, $addressFields))
                            {{ $addressData[$field] ?: '-' }}

                        @elseif (in_array($field, $bankFields))
                            {{ $bankData[$field] ?: '-' }}

                        @elseif (in_array($field, $liableFields))
                            {{ $liableData[$field] ?: '-' }}

                        @elseif ($field === 'type')
                            {{ __('messages.Dashboard ' . ucfirst($company->type)) }}
                        @else
                            {{ $company->{$field} ?? '-' }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($selectedFields) + 1 }}" class="no-data">
                    {{ __('data_tools.Tidak ada data') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
