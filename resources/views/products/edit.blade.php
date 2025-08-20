@extends('layouts.app')

@section('title', 'Edit Produk - CV. Agung')
@section('page-title', 'Edit Produk')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk', $product->nama_produk) }}" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stok</label>
                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
            </div>

            <!-- <div class="mb-3">
                <label for="minimum_stock" class="form-label">Minimum Stok</label>
                <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', $product->minimum_stock) }}">
            </div> -->

           

            <!-- <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ $product->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div> -->

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
