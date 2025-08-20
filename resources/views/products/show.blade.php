@extends('layouts.app')

@section('title', 'Detail Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Produk</h5>
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
       

            {{-- Informasi Produk --}}
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Nama Produk</th>
                        <td>{{ $product->nama_produk }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $product->category->nama_kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $product->deskripsi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>
                            <span class="badge {{ $product->stok <= $product->minimum_stock ? 'bg-danger' : 'bg-success' }}">
                                {{ $product->stok }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Minimum Stok</th>
                        <td>{{ $product->minimum_stock }}</td>
                    </tr>
                   
                    <tr>
                        <th>Tanggal Ditambahkan</th>
                        <td>{{ $product->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diperbarui</th>
                        <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-3">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Produk
                </a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus Produk
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
