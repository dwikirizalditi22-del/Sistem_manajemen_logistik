@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Manajemen Produk')



@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0"  class="btn btn-primary">Daftar Produk</h5>
            </div>
           
            <div class="col-auto">
            @if(in_array(Auth::user()->role, ['admin', 'store_manager']))
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus me-1"></i> Tambah Produk Baru
        </a>
        @endif
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari produk..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
               
            </div>
            
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <!-- <th>Code</th> -->
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        
                        <!-- <th>Status</th> -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products ?? [] as $product)
                    <tr>
                        <!-- <td>{{ $product->code }}</td> -->
                        <td>{{ $product->nama_produk }}</td>
                        <td>{{ $product->category->nama_kategori ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $product->stok <= $product->minimum_stock ? 'bg-danger' : 'bg-success' }}">
                                {{ $product->stok}}
                            </span>
                        </td>
                       
                        <!-- <td>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td> -->
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada produk ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        responsive: true,
        pageLength: 25
    });
});
</script>
@endsection