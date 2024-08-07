<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang - {{ $tanggal }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Barang Bulanan</h1>
    <h3>Tanggal Laporan: {{ formatTanggal($tanggal) }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang Masuk</th>
                <th>Jumlah Barang Keluar</th>
                <th>Penghasilan</th>
                <th>Keuntungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->jumlah_barang_masuk }}</td>
                    <td>{{ $item->jumlah_barang_keluar }}</td>
                    <td>{{ formatRupiah($item->penghasilan, 2) }}</td>
                    <td>{{ formatRupiah($item->keuntungan, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
