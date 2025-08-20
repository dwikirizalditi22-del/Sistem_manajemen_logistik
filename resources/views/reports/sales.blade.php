{{-- resources/views/reports/sales.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #7E1014, #DBDBDB);">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i> History Penjualan per Bulan
            </h5>
        </div>
        <div class="card-body">
            @php
                // Grouping penjualan berdasarkan bulan-tahun
                $salesByMonth = $sales->groupBy(function($sale) {
                    return \Carbon\Carbon::parse($sale->tanggal)->format('F Y'); 
                });
            @endphp

            @forelse($salesByMonth as $month => $monthlySales)
                <h5 class="mt-4 mb-3 text-primary">
                    <i class="fas fa-calendar-alt me-2"></i>{{ $month }}
                </h5>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Terjual</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySales as $sale)
                                @foreach($sale->saleDetails as $detail)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $detail->product->nama_produk }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total Bulan Ini:</th>
                                <th>
                                    Rp {{ number_format($monthlySales->flatMap->saleDetails->sum(fn($d) => $d->jumlah * $d->harga), 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @empty
                <p class="text-muted">Belum ada data penjualan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
