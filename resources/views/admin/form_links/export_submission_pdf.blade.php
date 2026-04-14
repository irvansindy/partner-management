<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #000;
        }

        /* ── HEADER ─────────────────────────────────────── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .header-logo td {
            vertical-align: middle;
        }

        .logo-img {
            height: 45px;
        }

        .company-info {
            font-size: 7.5pt;
            line-height: 1.5;
        }

        .company-name {
            font-size: 11pt;
            font-weight: bold;
        }

        .divider {
            border-top: 2px solid #000;
            margin: 4px 0 2px 0;
        }

        .divider-thin {
            border-top: 1px solid #000;
            margin: 2px 0;
        }

        /* ── TITLE ───────────────────────────────────────── */
        .form-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 8px 0 6px 0;
            text-transform: uppercase;
        }

        /* ── KATEGORI BADGE ──────────────────────────────── */
        .kategori-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .kategori-table td {
            vertical-align: top;
            font-size: 8.5pt;
        }

        .kategori-box {
            border: 1px solid #000;
            padding: 4px 8px;
            display: inline-block;
            margin-right: 3px;
            font-size: 8pt;
        }

        .kategori-box.checked {
            background-color: #000;
            color: #fff;
            font-weight: bold;
        }

        .material-box {
            border: 1px solid #555;
            padding: 3px 6px;
            margin: 1px 2px;
            font-size: 7.5pt;
            display: inline-block;
        }

        .material-box.checked {
            background-color: #555;
            color: #fff;
        }

        /* ── SECTION ─────────────────────────────────────── */
        .section {
            margin-bottom: 8px;
        }

        .section-title {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
        }

        .field-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .field-num {
            display: table-cell;
            width: 16px;
            font-weight: bold;
            vertical-align: top;
        }

        .field-content {
            display: table-cell;
            vertical-align: top;
        }

        /* ── DATA TABLE ──────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .data-table td {
            padding: 3px 5px;
            vertical-align: top;
            font-size: 8.5pt;
        }

        .data-table .label {
            width: 28%;
            font-weight: normal;
        }

        .data-table .colon {
            width: 2%;
            text-align: center;
        }

        .data-table .value {
            width: 70%;
            border-bottom: 1px solid #555;
            min-height: 14px;
        }

        .data-table .value-block {
            width: 70%;
            border-bottom: 1px solid #555;
            min-height: 22px;
            padding-bottom: 2px;
        }

        /* ── HALF-HALF TABLE ─────────────────────────────── */
        .half-table {
            width: 100%;
            border-collapse: collapse;
        }

        .half-table td {
            vertical-align: top;
        }

        .half-l {
            width: 50%;
            padding-right: 8px;
        }

        .half-r {
            width: 50%;
            padding-left: 8px;
        }

        /* ── CONTACT TABLE ───────────────────────────────── */
        .contact-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
        }

        .contact-table th {
            background-color: #d9d9d9;
            border: 1px solid #999;
            padding: 3px 5px;
            font-size: 8pt;
            text-align: center;
        }

        .contact-table td {
            border: 1px solid #999;
            padding: 3px 5px;
            font-size: 8pt;
            min-height: 16px;
        }

        /* ── ISO TABLE ───────────────────────────────────── */
        .iso-table {
            width: 60%;
            border-collapse: collapse;
            margin-top: 3px;
        }

        .iso-table td {
            padding: 2px 6px;
            font-size: 8.5pt;
        }

        .iso-label {
            width: 30%;
        }

        .iso-opts {
            width: 70%;
        }

        /* ── BANK TABLE ──────────────────────────────────── */
        .bank-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
        }

        .bank-table th {
            background-color: #d9d9d9;
            border: 1px solid #999;
            padding: 3px 5px;
            font-size: 8pt;
            text-align: center;
        }

        .bank-table td {
            border: 1px solid #999;
            padding: 3px 5px;
            font-size: 8pt;
        }

        /* ── CHECKBOX ────────────────────────────────────── */
        .cb {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 2px;
            vertical-align: middle;
            text-align: center;
            font-size: 7pt;
            line-height: 10px;
        }

        .cb.checked {
            background: #000;
            color: #fff;
        }

        /* ── STATEMENT ───────────────────────────────────── */
        .statement {
            border: 1px solid #aaa;
            padding: 8px 10px;
            font-size: 8pt;
            text-align: justify;
            margin: 8px 0;
            line-height: 1.5;
        }

        /* ── SIGNATURE ───────────────────────────────────── */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .sig-table td {
            vertical-align: top;
            font-size: 8.5pt;
            text-align: center;
        }

        .sig-box {
            border: 1px solid #aaa;
            height: 70px;
            margin: 4px 8px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 4px;
            font-size: 8pt;
            color: #888;
        }

        .sig-label {
            font-weight: bold;
            margin-top: 4px;
        }

        .sig-name-line {
            border-top: 1px solid #000;
            margin: 4px 20px 2px 20px;
        }

        /* ── SECTION HEADER ──────────────────────────────── */
        .sec-header {
            background-color: #e8e8e8;
            border-left: 4px solid #555;
            padding: 2px 6px;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 5px;
        }

        .outer-border {
            border: 1px solid #bbb;
            padding: 8px 10px;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>

    {{-- ═══════════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════════════ --}}
    <table class="header-table">
        <tr>
            <td style="width:15%;">
                <img src="{{ public_path('uploads/logo/logo.png') }}" class="logo-img" alt="PT Pralon">
            </td>
            <td style="width:85%;" class="company-info">
                <span class="company-name">PT Pralon</span><br>
                Synergy Building #08-08<br>
                Jl. Jalur Sutera Barat 17 Alam Sutera, Serpong Tangerang 15143 - Indonesia<br>
                Tangerang 15143 - Indonesia &nbsp;|&nbsp; +62 21 304 38808 &nbsp;|&nbsp; www.pralon.com
            </td>
        </tr>
    </table>
    <div class="divider"></div>
    <div class="divider-thin"></div>

    {{-- ═══════════════════════════════════════════════════
     FORM TITLE
══════════════════════════════════════════════════════ --}}
    <div class="form-title">Form Data Penyedia Eksternal</div>

    {{-- ═══════════════════════════════════════════════════
     KATEGORI & MATERIAL
══════════════════════════════════════════════════════ --}}
    @php
        $isLama = false; // bisa di-drive dari data jika ada field-nya
        $isBaru = true;
        $isPerubahan = false;
        $isPKP = !empty($company->npwp);
        $isNonPKP = empty($company->npwp);

        $bizType = strtolower($company->business_classification ?? '');
        $materialCategories = [
            'Spare Part & Maintenance' => str_contains($bizType, 'spare') || str_contains($bizType, 'maintenance'),
            'HRGA & IT' => str_contains($bizType, 'hrga') || str_contains($bizType, 'it'),
            'Resales Good' => str_contains($bizType, 'resale'),
            'Consumables' => str_contains($bizType, 'consumable'),
            'Material' => str_contains($bizType, 'material') || str_contains($bizType, 'manufacturer'),
        ];
    @endphp

    <table class="kategori-table">
        <tr>
            <td style="width:60%;">
                <strong>Kategori :</strong>&nbsp;&nbsp;
                <span class="kategori-box {{ $isLama ? 'checked' : '' }}">Lama</span>
                <span class="kategori-box {{ $isBaru ? 'checked' : '' }}">Baru</span>
                <span class="kategori-box {{ $isPerubahan ? 'checked' : '' }}">Perubahan</span>
                &nbsp;&nbsp;&nbsp;
                <span class="kategori-box {{ $isPKP ? 'checked' : '' }}">PKP</span>
                <span class="kategori-box {{ $isNonPKP ? 'checked' : '' }}">Non PKP</span>
            </td>
            <td style="width:40%; vertical-align:top;">
                <strong>Material :</strong><br>
                @foreach ($materialCategories as $label => $active)
                    <span class="material-box {{ $active ? 'checked' : '' }}">{{ $label }}</span>
                @endforeach
            </td>
        </tr>
    </table>
    <div class="divider-thin"></div>

    <div style="height:5px;"></div>

    {{-- ═══════════════════════════════════════════════════
     SECTION 1 — IDENTITAS PERUSAHAAN
══════════════════════════════════════════════════════ --}}
    <div class="outer-border">
        <div class="sec-header">1 &nbsp; Nama &amp; Identitas Perusahaan</div>
        <table class="data-table">
            <tr>
                <td class="label">Nama Perusahaan</td>
                <td class="colon">:</td>
                <td class="value"><strong>{{ $company->name ?? '' }}</strong></td>
                <td style="width:2%;"></td>
                <td class="label" style="width:22%;">Tahun Pendirian</td>
                <td class="colon">:</td>
                <td class="value" style="width:18%;">{{ $company->established_year ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Usaha</td>
                <td class="colon">:</td>
                <td class="value">{{ ucfirst($company->type ?? '') }} &nbsp;|&nbsp;
                    {{ $company->business_classification ?? '' }}</td>
                <td></td>
                <td class="label">Jumlah Karyawan</td>
                <td class="colon">:</td>
                <td class="value">{{ $company->total_employee ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Detail / Bidang Usaha</td>
                <td class="colon">:</td>
                <td class="value" colspan="5">{{ $company->business_classification_detail ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Email Koresponden</td>
                <td class="colon">:</td>
                <td class="value">{{ $company->email_address ?? '' }}</td>
                <td></td>
                <td class="label">Website</td>
                <td class="colon">:</td>
                <td class="value">{{ $company->website_address ?? '' }}</td>
            </tr>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════
     SECTION 2 — ALAMAT KANTOR (Address pertama)
══════════════════════════════════════════════════════ --}}
    @php $addr1 = $company->address->first(); @endphp
    <div class="outer-border">
        <div class="sec-header">2 &nbsp; Alamat Kantor</div>
        <table class="data-table">
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value-block" colspan="5">{{ optional($addr1)->address ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Kota</td>
                <td class="colon">:</td>
                <td class="value">{{ optional($addr1)->city ?? '' }}</td>
                <td></td>
                <td class="label" style="width:22%;">Kode Pos</td>
                <td class="colon">:</td>
                <td class="value" style="width:18%;">{{ optional($addr1)->zip_code ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">No Telp / Fax</td>
                <td class="colon">:</td>
                <td class="value">{{ optional($addr1)->telephone ?? '' }} &nbsp; / &nbsp;
                    {{ optional($addr1)->fax ?? '' }}</td>
                <td colspan="4"></td>
            </tr>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════
     SECTION 3 — ALAMAT LAIN (Address kedua dst, jika ada)
══════════════════════════════════════════════════════ --}}
    @php $otherAddresses = $company->address->skip(1); @endphp
    <div class="outer-border">
        <div class="sec-header">3 &nbsp; Alamat Lain (Surat Menyurat)</div>
        @if ($otherAddresses->isNotEmpty())
            @foreach ($otherAddresses as $idx => $addr)
                <table class="data-table" style="{{ $idx > 0 ? 'margin-top:5px;border-top:1px dashed #ccc;' : '' }}">
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="colon">:</td>
                        <td class="value-block" colspan="5">{{ $addr->address ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kota</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $addr->city ?? '' }}</td>
                        <td></td>
                        <td class="label" style="width:22%;">Kode Pos</td>
                        <td class="colon">:</td>
                        <td class="value" style="width:18%;">{{ $addr->zip_code ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">No Telp / Fax</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $addr->telephone ?? '' }} &nbsp; / &nbsp; {{ $addr->fax ?? '' }}</td>
                        <td colspan="4"></td>
                    </tr>
                </table>
            @endforeach
        @else
            <table class="data-table">
                <tr>
                    <td class="label">Alamat</td>
                    <td class="colon">:</td>
                    <td class="value-block" colspan="5"></td>
                </tr>
                <tr>
                    <td class="label">Kota</td>
                    <td class="colon">:</td>
                    <td class="value"></td>
                    <td></td>
                    <td class="label" style="width:22%;">Kode Pos</td>
                    <td class="colon">:</td>
                    <td class="value" style="width:18%;"></td>
                </tr>
                <tr>
                    <td class="label">No Telp / Fax</td>
                    <td class="colon">:</td>
                    <td class="value"></td>
                    <td colspan="4"></td>
                </tr>
            </table>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════
     SECTION 4 — CONTACT PERSON / PIC
══════════════════════════════════════════════════════ --}}
    <div class="outer-border">
        <div class="sec-header">4 &nbsp; Contact Person / PIC</div>
        <table class="contact-table">
            <thead>
                <tr>
                    <th style="width:20%;">Dept / Bag</th>
                    <th style="width:25%;">Nama</th>
                    <th style="width:25%;">Telp / Ext / HP</th>
                    <th style="width:30%;">Email</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $contactRows = ['Direksi', 'Finance / Acc', 'Marketing'];
                    $contacts = $company->contact ?? collect();
                @endphp
                {{-- Render baris dari data yang ada --}}
                @forelse ($contacts as $c)
                    <tr>
                        <td>{{ $c->department ?? '-' }}</td>
                        <td>{{ $c->name ?? '' }}</td>
                        <td>{{ $c->telephone ?? '' }}</td>
                        <td>{{ $c->email ?? '' }}</td>
                    </tr>
                @empty
                @endforelse
                {{-- Tambahkan baris kosong agar minimal 3 baris --}}
                @for ($i = $contacts->count(); $i < 3; $i++)
                    <tr>
                        <td style="height:16px;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════
     SECTION 5 — DATA PERUSAHAAN (NPWP)
══════════════════════════════════════════════════════ --}}
    <div class="outer-border">
        <div class="sec-header">5 &nbsp; Data Perusahaan</div>
        <table class="half-table">
            <tr>
                <td class="half-l">
                    <table class="data-table">
                        <tr>
                            <td class="label">No Pengukuhan PKP</td>
                            <td class="colon">:</td>
                            <td class="value">{{ $company->register_number_as_in_tax_invoice ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">No NPWP</td>
                            <td class="colon">:</td>
                            <td class="value">{{ $company->npwp ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nama NPWP</td>
                            <td class="colon">:</td>
                            <td class="value">{{ $company->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Alamat NPWP</td>
                            <td class="colon">:</td>
                            <td class="value-block">{{ optional($addr1)->address ?? '' }}</td>
                        </tr>
                    </table>
                </td>
                <td class="half-r" style="vertical-align:top; font-size:8pt;">
                    <strong>Copy dok. yang harus dilampirkan :</strong><br><br>
                    @php
                        $docs = $company->attachment ?? collect();
                        $docNames = $docs->pluck('filename')->toArray();
                        $requiredDocs = [
                            'NPWP',
                            'Surat Pengukuhan PKP',
                            'Surat Keterangan Terdaftar',
                            'Company Profile',
                        ];
                    @endphp
                    @foreach ($requiredDocs as $doc)
                        @php
                            $hasDoc = collect($docNames)->contains(
                                fn($n) => str_contains(strtolower($n), strtolower($doc)),
                            );
                        @endphp
                        <span class="cb {{ $hasDoc ? 'checked' : '' }}">{{ $hasDoc ? '✓' : '' }}</span>
                        {{ $doc }}<br>
                    @endforeach
                    @if ($docs->isNotEmpty())
                        <div style="margin-top:6px; font-size:7.5pt; color:#333;">
                            <em>Lampiran ({{ $docs->count() }} file):</em><br>
                            @foreach ($docs as $doc)
                                • {{ $doc->filename }}<br>
                            @endforeach
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════
     SECTION 6 — KELENGKAPAN ISO
══════════════════════════════════════════════════════ --}}
    <div class="outer-border">
        <div class="sec-header">6 &nbsp; Kelengkapan ISO</div>
        @php
            $isoRaw = strtolower($company->system_management ?? '');
            $isoMap = [
                'ISO 9001' => str_contains($isoRaw, '9001'),
                'ISO 45001' => str_contains($isoRaw, '45001'),
                'ISO 14000' => str_contains($isoRaw, '14000') || str_contains($isoRaw, '14001'),
                'Other' =>
                    !empty($isoRaw) &&
                    !str_contains($isoRaw, '9001') &&
                    !str_contains($isoRaw, '45001') &&
                    !str_contains($isoRaw, '14000'),
            ];
            $otherIso = $isoMap['Other'] ? $company->system_management : '';
        @endphp
        <table class="iso-table">
            @foreach ($isoMap as $isoName => $active)
                <tr>
                    <td class="iso-label">{{ $isoName }}</td>
                    <td class="colon">:</td>
                    <td class="iso-opts">
                        <span class="cb {{ $active ? 'checked' : '' }}">{{ $active ? '✓' : '' }}</span> Diterapkan
                        &nbsp;&nbsp;
                        <span class="cb {{ $active ? 'checked' : '' }}">{{ $active ? '✓' : '' }}</span>
                        Tersertifikasi
                        @if ($isoName === 'Other' && $otherIso)
                            &nbsp;&nbsp; ISO : <u>{{ $otherIso }}</u>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════
     SECTION 7 — PAYMENT
══════════════════════════════════════════════════════ --}}
    <div class="outer-border">
        <div class="sec-header">7 &nbsp; Payment</div>
        <table class="bank-table">
            <thead>
                <tr>
                    <th style="width:30%;">Nama Bank</th>
                    <th style="width:35%;">Nama Akun / Atas Nama</th>
                    <th style="width:35%;">Nomor Rekening</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($company->bank as $bank)
                    <tr>
                        <td>{{ $bank->name ?? '' }}</td>
                        <td>{{ $bank->account_name ?? '' }}</td>
                        <td>{{ $bank->account_number ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td style="height:16px;"></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <table class="data-table" style="margin-top:5px;">
            <tr>
                <td class="label" style="width:22%;">Term of Payment</td>
                <td class="colon">:</td>
                <td class="value">{{ $company->term_of_payment ?? '' }} hari</td>
                <td style="width:2%;"></td>
                <td class="label" style="width:22%;">Batas Kredit</td>
                <td class="colon">:</td>
                <td class="value">
                    {{ $company->credit_limit ? 'Rp ' . number_format($company->credit_limit, 0, ',', '.') : '' }}</td>
            </tr>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════
     PERNYATAAN
══════════════════════════════════════════════════════ --}}
    <div class="statement">
        Dengan ini saya menyatakan bahwa informasi yang saya berikan di atas adalah benar, apabila dikemudian hari
        ditemukan
        dan terjadi penyimpangan dalam penggunaannya, maka saya bersedia menyelesaikan sesuai dengan hukum yang
        berlaku.<br><br>
        Dan saya sebagai Penyedia Eksternal menyatakan kesediaan mengikuti aturan administrasi yang telah ditentukan dan
        berlaku di PT Pralon.<br><br>
        Demikian surat pernyataan ini saya buat dengan sadar dan tanpa paksaan untuk dapat dipergunakan sebagaimana
        mestinya.
    </div>

    {{-- ═══════════════════════════════════════════════════
    TANDA TANGAN
══════════════════════════════════════════════════════ --}}
    @php
        $liable = $company->liablePeople->first();
    @endphp
    <table class="sig-table">
        <tr>
            <td style="width:70%;"></td>
            <td style="width:30%; text-align:center;">
                <div style="font-size:8pt;">
                    .......................,
                    {{ \Carbon\Carbon::parse($company->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                </div>
                <div style="font-size:8pt; margin-top:2px;">Nama Perusahaan</div>
                <div
                    style="height:55px; border: 1px solid #aaa; margin: 4px 0; display:flex; align-items:flex-end; justify-content:center; padding-bottom:3px;">
                    <span style="font-size:7.5pt; color:#aaa;">(ttd + stempel)</span>
                </div>
                <div class="sig-name-line"></div>
                <div style="font-size:8.5pt; font-weight:bold;">
                    {{ optional($liable)->name ?? '______________________' }}</div>
                <div style="font-size:8pt; color:#555;">{{ optional($liable)->position ?? '' }}</div>
            </td>
        </tr>
    </table>

</body>

</html>
