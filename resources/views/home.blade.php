@extends('layouts.dashboard')
@section('judul', 'Dashboard')

@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                @if ($barangWarning->count() > 0)
                    <div class="alert alert-warning" role="alert">
                        <strong>Peringatan!</strong> Stok barang berikut kurang dari 5:
                        <ul>
                            @foreach ($barangWarning as $barang)
                                <li>{{ $barang->nama_barang }} (Stok: {{ $barang->stok }})</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="alert alert-success" role="alert">
                        Semua barang memiliki stok yang cukup.
                    </div>
                @endif
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Jumlah Data Barang: {{ $barangCount }}</h5>

                        <!-- Chart Jumlah Stok -->
                        <canvas id="stokChart"></canvas>

                        {{-- <!-- Chart Barang Keluar -->
                        <canvas id="barangKeluarChart"></canvas>

                        <!-- Chart Barang Masuk -->
                        <canvas id="barangMasukChart"></canvas> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctxStok = document.getElementById('stokChart').getContext('2d');
            // var ctxKeluar = document.getElementById('barangKeluarChart').getContext('2d');
            // var ctxMasuk = document.getElementById('barangMasukChart').getContext('2d');

            // Chart Jumlah Stok
            var stokChart = new Chart(ctxStok, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stokData->pluck('nama_barang')) !!},
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: {!! json_encode($stokData->pluck('stok')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Menjadikan chart horizontal
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }

            });

         
        });
    </script>
@endsection
