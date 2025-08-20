
@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Reports')
@section('content')
<div class="container">
    <h2 class="mb-4">📅History</h2>

    {{-- Filter tanggal --}}
    <form method="GET" action="{{ route('reports.index') }}" class="mb-4">
    <div class="row g-2">
        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>


    @php
        // Group per bulan (pakai created_at atau tanggal sesuai field di DB)
        $salesByMonth = $sales->groupBy(function($sale) {
            return \Carbon\Carbon::parse($sale->created_at)->format('F Y');
        });
    @endphp

    @forelse($salesByMonth as $month => $monthlySales)
        {{-- Judul Bulan --}}
        <h4 class="mt-4 mb-3 text-primary">
            <i class="fas fa-calendar-alt me-2"></i>{{ $month }}
        </h4>

        @php
            // Group per tanggal dalam bulan
            $salesByDate = $monthlySales->groupBy(function($sale) {
                return \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d');
            });
        @endphp

        @foreach($salesByDate as $date => $dailySales)
            <h5 class="mb-2">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h5>
            <table class="table table-bordered table-striped mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Nama Produk</th>
                        <th>Stok Tersisa</th>
                        <th>Satuan</th>
                        <th>Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailySales->flatMap->saleDetails as $detail)
                        <tr>
                            <td>{{ $detail->product->nama_produk }}</td>
                            <td>{{ $detail->product->stok }}</td>
                            <td>{{ $detail->product->satuan }}</td>
                            <td>{{ $detail->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @empty
        <p class="text-muted">Tidak ada data penjualan.</p>
    @endforelse
</div>
@endsection
