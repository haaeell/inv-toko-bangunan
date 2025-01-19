<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <h2>Laporan Barang Bulanan - {{ $tanggal->format('F Y') }}</h2>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Total Barang Keluar</th>
                <th>Total Pembelian</th>
                <th>Total Penjualan</th>
                <th>Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $item)
                <tr>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ $item->jumlah_keluar }}</td>
                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->harga_jual - $item->harga_beli, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                @php
                    $totalPenghasilan = $laporan->sum('harga_jual') - $laporan->sum('harga_beli');
                @endphp
                <th>{{ number_format($totalPenghasilan, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

</body>

</html>
