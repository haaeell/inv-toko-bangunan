@extends('layouts.dashboard')

@section('judul', 'Laporan Barang Bulanan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <form action="{{ route('laporan.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mt-3">
                                    <select name="bulan" class="form-control" id="bulan_laporan" required>
                                        @foreach(range(1, 12) as $month)
                                            @php
                                                $date = \Carbon\Carbon::create()->month($month)->format('Y-m');
                                                $formattedMonth = \Carbon\Carbon::create($date)->translatedFormat('F');
                                            @endphp
                                            <option value="{{ $month }}" {{ $bulan == $month ? 'selected' : '' }}>
                                                {{ $formattedMonth }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mt-3">
                                    <input type="number" name="tahun" class="form-control" id="tahun_laporan" value="{{ $tahun }}" required>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if($tanggal)
                        <div class="d-flex gap-2">
                            <form action="{{ route('laporan.cetak_pdf') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="tanggal_laporan" value="{{ $tanggal }}">
                                <button class="btn btn-info" type="submit">Cetak Laporan</button>
                            </form>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table" id="datatable">
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
                                    <td>{{ $item['nama_barang'] }}</td>
                                    <td>{{ $item['total_barang_keluar'] }}</td>
                                    <td>{{ number_format($item['total_pembelian'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($item['total_penjualan'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($item['penghasilan'], 0, ',', '.') }}</td>
                               
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto submit form saat bulan atau tahun berubah
        document.getElementById('bulan_laporan').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('tahun_laporan').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
@endsection
