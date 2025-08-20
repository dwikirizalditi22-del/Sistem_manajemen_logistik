@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detail Pembelian - {{ $purchase->kode_pembelian }}</h4>
                    <div>
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button onclick="window.print()" class="btn btn-info btn-sm">Print</button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Info Umum -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Informasi Pembelian</strong></h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150">Kode Pembelian</td>
                                    <td>: {{ $purchase->kode_pembelian }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>: {{ \Carbon\Carbon::parse($purchase->tanggal)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>: 
                                        <span class="badge badge-{{ $purchase->status == 'completed' ? 'success' : ($purchase->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>User</td>
                                    <td>: {{ $purchase->user->nama ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Informasi Supplier</strong></h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150">Nama Supplier</td>
                                    <td>: {{ $purchase->supplier->nama_supplier ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>: {{ $purchase->supplier->alamat ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td>: {{ $purchase->supplier->no_telepon ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <h6><strong>Detail Produk</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Produk</th>
                                    <th>Kode Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($purchase->purchaseDetails as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->product->nama_produk }}</td>
                                    <td>{{ $detail->product->kode_produk }}</td>
                                    <td class="text-center">{{ $detail->jumlah }}</td>
                                    
                                </tr>
                                @php $total += $detail->subtotal; @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>
                    </div>

                    <!-- Catatan -->
                    @if($purchase->notes)
                    <div class="mt-3">
                        <h6><strong>Catatan</strong></h6>
                        <p>{{ $purchase->notes }}</p>
                    </div>
                    @endif

                </div> <!-- card-body -->
            </div> <!-- card -->
        </div>
    </div>
</div>
@endsection
