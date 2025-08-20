@extends('layouts.app')

@section('title', 'Buat Penjualan')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Input Penjualan</h5>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>

        <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

            <div class="card-body">
                <div class="mb-3">
                    <label>Nama Barista</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                </div>

                <div class="mb-3">
                    <label>Tanggal Penjualan</label>
                    <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <hr>

             

                @foreach($categories as $category)
                    <h5>{{ $category->nama_kategori }}</h5>
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products as $product)
                            <tr>
                                <td>
                                    {{ $product->nama_produk }}

                                </td>
                                <td>{{ $product->stok }}</td>
                                <td>{{ $product->satuan }}</td>
                                <td>
                                    <input type="number" min="0" max="{{ $product->stok }}" name="items[{{ $product->id }}][jumlah]" class="form-control quantity-input" value="0">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Simpan Semua</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#saleForm').on('submit', function(e) {
        let isValid = true;
        let errorMessage = '';

        $('input.quantity-input').each(function() {
            const qty = parseInt($(this).val()) || 0;
            const max = parseInt($(this).attr('max')) || 0;
            const productName = $(this).closest('tr').find('td:first').text().trim();

            if (qty < 0) {
                isValid = false;
                errorMessage += `Jumlah untuk produk ${productName} tidak boleh negatif\n`;
            }

            if (qty > max) {
                isValid = false;
                errorMessage += `Stok tidak cukup untuk produk ${productName} (Tersedia: ${max}, Diminta: ${qty})\n`;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });
});
</script>
@endpush

@endsection
