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


    <h2>Laporan Barang Masuk - {{ $tanggal }}</h2>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                    <th>Total Barang Masuk</th>
                    <th>Total Harga Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $item)
            <tr>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->total_jumlah }}</td>
                <td>{{ number_format($item->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="2" class="text-right">Total:</th>
                @php
                    $totalJumlah = $laporanMasuk->sum('total_jumlah');
                    $totalHarga = $laporanMasuk->sum('total_harga');
                @endphp
                <th>{{ number_format($totalHarga, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
    <h2>Laporan Barang Masuk - {{ $tanggal }}</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Tanggal</th>
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
                    <td>{{ $item->created_at->format('d F Y H:i') }}</td>

                    <td>{{ $item->jumlah_keluar }}</td>
                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->harga_jual - $item->harga_beli, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="5" class="text-right">Total:</th>
                @php
                    $totalPenghasilan = $laporan->sum('harga_jual') - $laporan->sum('harga_beli');
                @endphp
                <th>{{ number_format($totalPenghasilan, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

</body>

</html>
