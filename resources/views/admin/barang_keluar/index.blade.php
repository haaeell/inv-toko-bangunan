@extends('layouts.dashboard')
@section('judul', 'Data Barang Keluar')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <button class="btn btn-primary rounded-3 mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalTambah">Tambah</button>
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
                                    <th>Nama Barang</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangKeluar as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>{{ $item->jumlah_keluar }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm rounded-3" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $item->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm rounded-3" onclick="deleteData({{ $item->id }})">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="modalEditLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditLabel">Edit Barang Keluar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formEdit{{ $item->id }}"
                                                        action="{{ route('barang_keluar.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="barang_id" value="{{ $item->barang_id }}">
                                                        <div class="mb-3">
                                                            <label for="edit_jumlah_keluar" class="form-label">Jumlah Keluar</label>
                                                            <input type="number" class="form-control" name="jumlah_keluar"
                                                                id="edit_jumlah_keluar" value="{{ $item->jumlah_keluar }}" required>
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
                   <h5 class="modal-title" id="modalTambahLabel">Tambah Barang Keluar</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                   <form action="{{ route('barang_keluar.store') }}" method="POST">
                       @csrf
                       <div class="mb-3">
                           <label for="barang_id" class="form-label">Barang</label>
                           <select class="form-control" name="barang_id" id="barang_id" required>
                               <option value="" disabled selected>Pilih Barang</option>
                               @foreach ($barangList as $barang)
                                   <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                               @endforeach
                           </select>
                       </div>
                       <div class="mb-3">
                           <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                           <input type="number" class="form-control" name="jumlah_keluar" id="jumlah_keluar" required>
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
function deleteData(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
