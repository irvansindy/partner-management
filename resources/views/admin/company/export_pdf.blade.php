<style>
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
<p class="sub">Tanggal Export: {{ date('d-m-Y H:i:s') }}</p>

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
</table>
