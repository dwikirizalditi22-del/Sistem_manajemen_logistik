@extends('layouts.app')

@section('title', 'Pembelian - CV. Agung')
@section('page-title', 'Manajemen Pembelian')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" class="form-control" id="searchInput" placeholder="Cari Pembelian...">
            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat Pembelian Baru
        </a>
    </div>
</div>

<!-- Filter -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Pemasok</label>
                        <select class="form-select" id="supplierFilter">
                            <option value="">Semua Pemasok</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="dateFrom">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="dateTo">
                    </div>
                    <div class="col-12 text-end mt-2">
                        <button type="button" class="btn btn-secondary" id="resetFilter">Reset</button>
                        <button type="button" class="btn btn-primary" id="applyFilter">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Pembelian -->
<div class="card">
    <div class="card-body">
        @if($purchases->count())
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Pembelian #</th>
                        <th>Tanggal</th>
                        <th>Pemasok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="purchaseTableBody">
                    @foreach($purchases as $purchase)
                    <tr>
                        <td><strong>{{ $purchase->kode_pembelian }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($purchase->tanggal)->format('d M Y') }}</td>
                        <td>
                            <strong>{{ $purchase->supplier->nama_supplier }}</strong><br>
                            <small class="text-muted">{{ $purchase->supplier->no_telepon }}</small>
                        </td>
                        <td>
                            @if($purchase->status == 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($purchase->status == 'completed')
                                <span class="badge bg-success">Accept</span>
                            @elseif($purchase->status == 'cancelled')
                                <span class="badge bg-danger">Canceled</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <!-- Tombol Lihat: Semua role bisa -->
                                <a href="{{ route('purchases.show', $purchase->id) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Hanya admin & store_manager -->
                                @if(in_array(Auth::user()->role, ['admin', 'store_manager']))

                                    <!-- Edit -->
                                    <a href="{{ route('purchases.edit', $purchase->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Accept / Cancel -->
                                    @if($purchase->status == 'pending')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                onclick="updateStatus({{ $purchase->id }}, 'completed')" 
                                                title="Terima">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="updateStatus({{ $purchase->id }}, 'cancelled')" 
                                                title="Batalkan">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    <!-- Delete -->
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="deletePurchase({{ $purchase->id }})" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <small>Menampilkan {{ $purchases->firstItem() }} - {{ $purchases->lastItem() }} dari {{ $purchases->total() }} data</small>
            {{ $purchases->links() }}
        </div>

        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada data pembelian</h5>
            <a href="{{ route('purchases.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Buat Pembelian Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pembelian ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Search
    $('#searchInput').on('keyup', function() {
        const val = $(this).val().toLowerCase();
        $('#purchaseTableBody tr').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(val));
        });
    });

    // Filter
    $('#applyFilter').on('click', function () {
        const params = new URLSearchParams();
        if ($('#supplierFilter').val()) params.append('supplier', $('#supplierFilter').val());
        if ($('#statusFilter').val()) params.append('status', $('#statusFilter').val());
        if ($('#dateFrom').val()) params.append('from', $('#dateFrom').val());
        if ($('#dateTo').val()) params.append('to', $('#dateTo').val());

        window.location.href = '{{ route("purchases.index") }}?' + params.toString();
    });

    $('#resetFilter').on('click', function () {
        window.location.href = '{{ route("purchases.index") }}';
    });

    // Delete (fix modal for Bootstrap 5)
    function deletePurchase(id) {
        const url = '{{ route("purchases.destroy", ":id") }}'.replace(':id', id);
        $('#deleteForm').attr('action', url);

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // Update Status (fix CSRF and method injection)
    function updateStatus(id, status) {
        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("purchases.update", ":id") }}'.replace(':id', id)
        });

        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: '_method',
            value: 'PUT'
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: 'status',
            value: status
        }));

        $('body').append(form);
        form.submit();
    }
</script>
@endsection
