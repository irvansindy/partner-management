<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export Partner Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        @page {
            header: page-header;
            footer: page-footer;
            margin-top: 0px !important;
            margin-bottom: 2.54cm;
            margin-left: 2.175cm;
            margin-right: 2.175cm;
        }
        table {
            border-collapse: collapse;
        }
        thead {
            vertical-align: bottom;
            text-align: center;
            font-weight: bold;
        }
        tfoot {
            text-align: center;
            font-weight: bold;
        }
        th {
            text-align: left;
            padding-left: 0.35em;
            padding-right: 0.35em;
            padding-top: 0.35em;
            padding-bottom: 0.35em;
            vertical-align: top;
        }
        td {
            padding-left: 0.35em;
            padding-right: 0.35em;
            padding-top: 0.35em;
            padding-bottom: 0.35em;
            vertical-align: top;
        }
        img {
            margin: 0.2em;
            vertical-align: middle;
        }
        /* #content {
            margin-top: 100px !important;
        } */
        #address {
            font-size: 10px;
            margin-top: 0px !important;
        }
    </style>
</head>
<body>
    {{-- <htmlpageheader name="page-header">
        </htmlpageheader> --}}
    <img src="uploads/logo/logo.png" style="width: 21mm; height: 25mm; margin: 0;" />
    <br>
    <span id="address">Synergy Building #08-08
        Jl. Jalur Sutera Barat 17 Alam Sutera, Serpong Tangerang 15143 - Indonesia
        Tangerang 15143 - Indonesia +62 21 304 38808</span>

    <div id="content" style="">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Company Name</th>
                    <th>Company Group Name</th>
                </tr>
            </thead>
            <tbody>
                @php
                // dd($partners);
                    $no = 1;
                @endphp
                @forelse ($partners as $partner)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            {{ $partner->name }}
                        </td>
                        <td>
                            {{ $partner->group_name }}
                        </td>
                    </tr>
                @empty
                    
                @endforelse
            </tbody>
        </table>
    </div>

    <htmlpagefooter name="page-footer">
        <img src="uploads/logo/logo.png" style="width: 21mm; height: 25mm; margin: 0;" />
        {{-- <div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
        </div> --}}
    </htmlpagefooter>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>