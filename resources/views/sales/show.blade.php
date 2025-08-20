@extends('layouts.app')

@section('title', 'Detail Penjualan')
@section('page-title', 'Detail Penjualan')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Informasi Penjualan</h4>
        <p><strong>Tanggal Penjualan:</strong> {{ \Carbon\Carbon::parse($sale->tanggal)->format('d M Y') }}</p>
        

        <hr>
        <h5>Detail Item</h5>

        @php
            // Hitung total pengeluaran stok hari ini
            $totalPengeluaran = $sale->saleDetails->sum('jumlah');
        @endphp

        <p><strong>Total Pengeluaran Stok Hari Ini:</strong> {{ $totalPengeluaran }}</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Pengeluaran Stok</th>
                    <th>Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleDetails as $detail)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('d M Y') }}</td>
                    <td>{{ $detail->product->nama_produk ?? '-' }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ $detail->product->stok ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
