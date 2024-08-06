@extends('layouts.dashboard')
@section('judul', 'Laporan Barang Bulanan')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <form action="{{ route('laporan.generate') }}" method="POST" id="generateForm">
                        @csrf
                        <div class="input-group mt-3">
                            <input type="month" name="tanggal_laporan" class="form-control" id="tanggal_laporan" required>
                            <button class="btn btn-success" type="submit">Simpan Laporan</button>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang Masuk</th>
                                    <th>Jumlah Barang Keluar</th>
                                    <th>Penghasilan</th>
                                    <th>Keuntungan</th>
                                    <th>Tanggal Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->jumlah_barang_masuk }}</td>
                                        <td>{{ $item->jumlah_barang_keluar }}</td>
                                        <td>{{ number_format($item->penghasilan, 2) }}</td>
                                        <td>{{ number_format($item->keuntungan, 2) }}</td>
                                        <td>{{ $item->tanggal_laporan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('laporan.cetak_pdf') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="text" name="tanggal_laporan" id="pdf_tanggal_laporan">
                        <button class="btn btn-info" type="submit">Cetak Laporan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('tanggal_laporan').addEventListener('change', function() {
            const tanggal = this.value;
            window.location.href = "{{ route('laporan.index') }}?tanggal=" + tanggal;
        });

        document.getElementById('generateForm').addEventListener('submit', function() {
            document.getElementById('pdf_tanggal_laporan').value = document.getElementById('tanggal_laporan').value;
        });
    </script>
    @endpush
@endsection
