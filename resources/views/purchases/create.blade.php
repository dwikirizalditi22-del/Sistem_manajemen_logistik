@extends('layouts.app')

@section('title', 'Tambah Pembelian')
@section('page-title', 'Tambah Pembelian Baru')

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
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-header">
                <h5>Form Tambah Pembelian</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm">
                    @csrf

                    <!-- Supplier -->
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Pembelian</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>

                    <!-- Tabel Produk -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th width="50%">Produk</th>
                                    <th width="20%">Kuantitas</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Item akan ditambahkan via JS -->
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-success" id="addItem">+ Tambah Item</button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addItemBtn = document.getElementById('addItem');
    const itemsBody = document.getElementById('itemsBody');
    let itemIndex = 0;

    // Data produk dari backend (dikirim ke JS)
    const products = @json($products);

    addItemBtn.addEventListener('click', function() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select class="form-select product-select" name="items[${itemIndex}][product_id]" required>
                    <option value="">Pilih Produk</option>
                    ${products.map(product => `
                        <option value="${product.id}">${product.nama_produk}</option>
                    `).join('')}
                </select>
            </td>
            <td>
                <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" min="1" value="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        itemsBody.appendChild(row);
        itemIndex++;
    });

    // Hapus baris item
    itemsBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('tr').remove();
        }
    });

    // Validasi sebelum submit
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        const itemsCount = document.querySelectorAll('#itemsBody tr').length;
        if (itemsCount === 0) {
            e.preventDefault();
            alert('Silakan tambahkan minimal satu item pembelian.');
            return false;
        }

        let isValid = true;
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');

            if (!productSelect.value || !quantityInput.value) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Produk dan kuantitas wajib diisi.');
            return false;
        }
    });
});
</script>
@endsection
