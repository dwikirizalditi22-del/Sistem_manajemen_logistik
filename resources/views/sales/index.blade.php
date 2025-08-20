@extends('layouts.app')

@section('title', 'Daftar Penjualan')
@section('page-title', 'Stock Opname')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Stock Opname</h5>
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">+ Tambah Penjualan</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($sales->isEmpty())
                <p class="text-muted">Belum ada data penjualan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Barista</th>
                                
                
                                <th>Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $index => $sale)
                                <tr>
                                    <td>{{ $sales->firstItem() + $index }}</td>
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
                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus penjualan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
