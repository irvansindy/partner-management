<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> --}}
    <title>Export Partner Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    {{-- <htmlpageheader name="page-header">
        <table class="table-stepper" width="100%">
            <tr>
                <td style="padding-left:10px;">
                    <span style="font-size: 6px; font-weight: bold;margin-top:-10px"><img src="uploads/logo/icon.png"
                            width="70px" style="float: right;" />'</span>
                    <br>
                    <span style="font-size:8px;">Synergy Building #08-08</span>
                    <br>
                    <span style="font-size:8px;">Jl. Jalur Sutera Barat 17 Alam Sutera, Serpong Tangerang 15143 -
                        Indonesia</span>
                    <br>
                    <span style="font-size:8px;">Tangerang 15143 - Indonesia +62 21 304 38808</span>
                </td>
            </tr>
        </table>
    </htmlpageheader> --}}

    <h1 style="margin-top: 20px;">
        <b>List Daftar Vendor</b>
    </h1>
    <div class="table-stepper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Nama Grup</th>
                    <th>Tipe</th>
                    <th>Tahun Berdiri</th>
                    <th>Jumlah Karyawan</th>
                    <th>Pemilik Perusahaan</th>
                    <th>Email Perusahaan</th>
                    <th>No. Tlp</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // dd($partners);
                    $no = 1;
                @endphp
                @forelse ($partners as $partner)
                    <tr>
                        <td style="text-align: left;">{{ $no++ }}</td>
                        <td style="text-align: left; width:20%">
                            {{ $partner->name }}
                        </td>
                        <td style="text-align: left; width:10%">
                            {{ $partner->group_name }}
                        </td>
                        <td style="text-align: left;">
                            {{ $partner->type }}
                        </td>
                        <td style="text-align: left;">
                            {{ $partner->established_year . ' Masehi' }}
                        </td>
                        <td style="text-align: left;">
                            {{ $partner->total_employee }}
                        </td>
                        <td style="text-align: left;">
                            {{ $partner->owner_name != null ? $partner->owner_name : 'Kosong'}}
                        </td>
                        <td style="text-align: left;">
                            {{ $partner->email_address }}
                        </td>
                        <td style="text-align: left;">
                            {{ $partner->website_address }}
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- <htmlpagefooter name="page-footer">
        <hr>
        <table width="100%" style="font-size: 10px;">
            <tr>
                <td width="100%" align="left">
                    <p>
                        <b>Disclaimer</b>
                        <br>
                        this document is strictly private, confidential
                        and personal to recipients and should not be copied, distributed or reproduced in whole or in
                        part,
                        not passed to any third party.
                    </p>
                </td>
                <td width="10%" style="text-align: right;"> {PAGENO}</td>
            </tr>
        </table>
    </htmlpagefooter> --}}
</body>

</html>
<style>
    .table-stepper {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 10px;
        width: 100% !important;
        border: 1px solid #ddd;
    }

    .table-stepper tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table-stepper tr:hover {
        background-color: #ddd;
    }

    .table-stepper th {
        border: 1px solid #ddd;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 9px;
        text-align: center;
        background-color: #26577C;
        color: white;
        overflow-x: auto !important;
    }

    .table-stepper td,
    .datatable-stepper th {
        border: 1px solid #ddd;
        padding: 8px;

    }

    table tr:last-child {
        font-weight: bold;
    }







    .table-general {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 9px;
        width: 100% !important;
        border: 1px solid #ddd;

    }

    .table-general tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table-general tr:hover {
        background-color: #ddd;
    }

    .table-general th {
        border: 1px solid rgb(182, 181, 181);
        padding-top: 5px;
        font-size: 9px;
        padding-bottom: 5px;
        text-align: center;
        /* background-color: #D61355; */
        color: rgb(123, 121, 121);
        overflow-x: auto !important;
    }

    .table-general td,
    .datatable-general th {
        /* border: 1px solid #ddd; */
        padding: 8px;

    }

    table tr:last-child {
        font-weight: bold;
    }
</style>
