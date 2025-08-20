@extends('layouts.app')

@section('title', 'Edit Penjualan')
@section('page-title', 'Edit Penjualan')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Edit Informasi Penjualan</h4>

        <!-- Form Edit Penjualan -->
        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Penjualan</label>
                <input type="date" name="tanggal" id="tanggal" 
                       class="form-control" 
                       value="{{ old('tanggal', $sale->tanggal) }}" required>
            </div>

            <hr>
            <h5>Edit Detail Item</h5>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah Pengeluaran Stok</th>
                        <th>Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleDetails as $index => $detail)
                    <tr>
                        <td>
                            <select name="details[{{ $index }}][product_id]" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                        {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" 
                                   name="details[{{ $index }}][jumlah]" 
                                   class="form-control" 
                                   min="1"
                                   value="{{ old('details.'.$index.'.jumlah', $detail->jumlah) }}" 
                                   required>
                        </td>
                        <td>
                            {{ $detail->product->stok ?? 0 }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
