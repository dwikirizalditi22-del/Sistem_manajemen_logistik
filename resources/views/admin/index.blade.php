@extends('layouts.app')

@section('title', 'Dashboard - PT.Bumi Berkah Boga')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card shadow-sm">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Produk</div>
                        <div class="h5 mb-0 font-weight-bold text-primary">{{ $totalProduk ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card shadow-sm">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Stok Rendah</div>
                        <div class="h5 mb-0 font-weight-bold text-warning">{{ $lowStockItems ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</div>

<div class="row">
    <!-- Recent Sales -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">5 Penjualan Terbaru</h5>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($latestSales->isEmpty())
                    <p class="text-muted">Belum ada penjualan.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Barista</th>
                                    <th>Produk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestSales as $sale)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $sale->user->nama ?? '-' }}</td>
                                        <td>
                                            <ul class="mb-0">
                                                @foreach($sale->saleDetails as $detail)
                                                    <li>{{ $detail->product->nama_produk }} ({{ $detail->jumlah }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Peringatan Stok Rendah</h5>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Produk</a>
            </div>
            <div class="card-body">
                @forelse($lowStockProducts ?? [] as $product)
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $product->nama_produk }}</h6>
                        <small class="text-muted">Stok: {{ $product->stok }}</small>
                    </div>
                    <span class="badge bg-warning">Low</span>
                </div>
                @empty
                <p class="text-muted">Tidak ada produk dengan stok rendah</p>
                @endforelse
            </div>
        </div>
    </div>
    
    
    <div class="card mt-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Produk Paling Cepat Habis (30 Hari Terakhir)</h5>
    </div>
    <div class="card-body">
        @if($fastDepletingProducts->isEmpty())
            <p class="text-muted">Belum ada data penjualan dalam 30 hari terakhir.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Total Terjual</th>
                        <th>Sisa Stok</th>
                        <th>Kecepatan Habis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fastDepletingProducts as $product)
                        <tr>
                            <td>{{ $product->nama_produk }}</td>
                            <td>{{ $product->total_terjual }}</td>
                            <td>{{ $product->stok }}</td>
                            <td>
                                {{ $product->kecepatan_habis * 100 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

</div>

</div>
@endsection
