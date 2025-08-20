@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Pembelian - {{ $purchase->kode_pembelian }}</h4>
                    <a href="{{ route('purchases.index') }}" class="btn btn-secondary float-right">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nama_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal Pembelian</label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $purchase->tanggal }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ $purchase->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $purchase->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $purchase->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                           
                        </div>
                        <h5>Detail Pembelian</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchase->purchaseDetails as $detail)
                                    <tr>
                                        <td>{{ $detail->product->nama_produk }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Update Pembelian</button>
                            <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-info">Lihat Detail</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection