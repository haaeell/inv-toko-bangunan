@extends('layouts.dashboard')
@section('judul', 'Data Barang')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    @if (auth()->user()->role == 'admin')
                        <button class="btn btn-primary rounded-3 mb-3" data-toggle="modal"
                            data-target="#modalTambah">Tambah</button>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @elseif (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
                                    @if (auth()->user()->role == 'admin')
                                    <th>Aksi</th>
                                    @endif
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->gambar }}"
                                                class="img-fluid rounded-5" style="width: 100px;border-radius:12px">
                                        </td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ formatRupiah($item->harga_beli) }}</td>
                                        <td>{{ formatRupiah($item->harga_jual) }}</td>
                                        <td>{{ $item->jumlah_masuk - $item->jumlah_keluar }}</td>
                                        @if (auth()->user()->role == 'admin')
                                        <td>
                                            <button class="btn btn-info btn-sm rounded-3" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $item->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm rounded-3 btn-hapus"
                                                data-id="{{ $item->id }}"
                                                data-url="{{ route('barang.destroy', $item->id) }}">Hapus</button>

                                        </td>
                                        @endif
                                      
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="modalEditLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditLabel">Edit Barang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formEdit{{ $item->id }}"
                                                        action="{{ route('barang.update', $item->id) }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" id="edit_id" name="id"
                                                            value="{{ $item->id }}">
                                                        <div class="mb-3">
                                                            <label for="edit_nama_barang" class="form-label">Nama
                                                                Barang</label>
                                                            <input type="text" class="form-control" name="nama_barang"
                                                                id="edit_nama_barang" value="{{ $item->nama_barang }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="edit_harga_beli" class="form-label">Harga
                                                                Beli</label>
                                                            <input type="text" class="form-control" name="harga_beli"
                                                                id="edit_harga_beli"
                                                                value="{{ formatRupiah($item->harga_beli) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="edit_harga_jual" class="form-label">Harga
                                                                Jual</label>
                                                            <input type="text" class="form-control" name="harga_jual"
                                                                id="edit_harga_jual"
                                                                value="{{ formatRupiah($item->harga_jual) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="edit_satuan" class="form-label">Satuan</label>
                                                            <input type="text" class="form-control" name="satuan"
                                                                id="edit_satuan" value="{{ $item->satuan }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit_kategori" class="form-label">Kategori</label>
                                                            <input type="text" class="form-control" name="kategori"
                                                                id="edit_kategori" value="{{ $item->kategori }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="edit_gambar" class="form-label">Gambar</label>
                                                            <input type="file" class="form-control" name="gambar"
                                                                id="edit_gambar">
                                                            @if ($item->gambar)
                                                                <img src="{{ asset('storage/' . $item->gambar) }}"
                                                                    alt="Gambar" class="img-fluid mt-2"
                                                                    style="width: 100px;">
                                                            @endif
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli</label>
                            <input type="text" class="form-control" name="harga_beli" id="harga_beli" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual</label>
                            <input type="text" class="form-control" name="harga_jual" id="harga_jual" required>
                        </div>

                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" name="satuan" id="satuan" required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="kategori" id="kategori" required>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" class="form-control" name="gambar" id="gambar">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Format Rupiah
            function formatRupiah(angka) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return 'Rp. ' + rupiah;
            }

            function handleFormatInput() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(formatRupiah(value));
            }

            function handleRemoveFormatting() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(value);
            }

            $('#harga_jual, #edit_harga_jual, #harga_beli, #edit_harga_beli').on('keyup', handleFormatInput);
            $('#harga_jual, #edit_harga_jual, #harga_beli, #edit_harga_beli').on('focus', handleRemoveFormatting);

            $('.btn-hapus').on('click', function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data barang ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Terhapus!',
                                    'Barang telah dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus barang.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
