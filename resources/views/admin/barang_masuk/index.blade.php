@extends('layouts.dashboard')
@section('judul', 'Data Barang Masuk')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    @if (Auth::user()->role == 'admin')
                        <button class="btn btn-primary rounded-3 mb-3" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">Tambah Barang Masuk</button>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Jumlah Masuk</th>
                                    <th>Harga Beli</th>
                                    <th>Total Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Tanggal</th>
                                    @if (Auth::user()->level == 'admin')
                                        <th>Aksi</th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangMasuk as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ formatRupiah($item->harga_beli) }}</td>
                                        <td>{{ formatRupiah($item->harga_beli * $item->jumlah) }}</td>
                                        <td>{{ formatRupiah($item->harga_jual) }}</td>
                                        <td>{{ formatTanggal($item->tanggal_masuk) }}</td>
                                        @if (Auth::user()->level == 'admin')
                                            <td>
                                                <a href="{{ route('barang_masuk.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm rounded-3">Edit</a>
                                                <form action="{{ route('barang_masuk.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm rounded-3">Hapus</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Barang Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('barang_masuk.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang</label>
                            <select name="barang_id" id="barang_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
                            <input type="number" class="form-control" name="jumlah_masuk" id="jumlah_masuk" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="form-labelMasuk">Harga Beli </label>
                            <input type="number" class="form-control" name="harga_beli" id="harga_beli" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual</label>
                            <input type="number" class="form-control" name="harga_jual" id="harga_jual" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
