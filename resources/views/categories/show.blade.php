@extends('layouts.app')

@section('title', 'Detail Kategori - Product')
@section('page-title', 'Detail Kategori')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informasi Kategori</h5>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nama Kategori</dt>
                    <dd class="col-sm-8">{{ $category->nama_kategori }}</dd>

                    <dt class="col-sm-4">Deskripsi</dt>
                    <dd class="col-sm-8">{{ $category->deskripsi ?? '-' }}</dd>

                    <dt class="col-sm-4">Jumlah Produk</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-info">{{ $category->products->count() }} produk</span>
                    </dd>

                    <dt class="col-sm-4">Dibuat Pada</dt>
                    <dd class="col-sm-8">{{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}</dd>

                    <dt class="col-sm-4">Terakhir Diperbarui</dt>
                    <dd class="col-sm-8">{{ $category->updated_at ? $category->updated_at->format('d M Y') : '-' }}</dd>
                </dl>

                @if(Auth::user()->role === 'admin')
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <button class="btn btn-danger" onclick="deleteCategory({{ $category->id }})">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Daftar Produk -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Daftar Produk dalam Kategori Ini</h5>
            </div>
            <div class="card-body">
                @if($category->products->isEmpty())
                    <p class="text-muted">Belum ada produk dalam kategori ini.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Deskripsi</th>
                                
                                    <th>Stok</th>
                                    <th>Unit</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                <tr>
                                    <td>{{ $product->nama_produk }}</td>
                                    <td>{{ $product->deskripsi ?? '-' }}</td>
                                   
                                    <td>{{ $product->stok }}</td>
                                    <td>{{ $product->satuan }}</td>
                                   
                                    <td>{{ $product->created_at ? $product->created_at->format('d M Y') : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus kategori ini?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteCategory(categoryId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/categories/${categoryId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
