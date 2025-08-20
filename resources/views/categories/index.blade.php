@extends('layouts.app')

@section('title', 'Categories - Product ')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kategori</h5>
                @if(in_array(Auth::user()->role, ['admin', 'store_manager']))
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Kategori Baru
                </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Search -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchCategories" placeholder="Cari kategori...">
                    </div>
                </div>

                <!-- Categories Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Produk</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories ?? [] as $category)
                            <tr>
                                <td>
                                    <strong>{{ $category->nama_kategori }}</strong>
                                </td>
                                <td>
                                    {{ $category->deskripsi ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->products_count ?? 0 }} produk</span>
                                </td>
                                <td>{{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-outline-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array(Auth::user()->role, ['admin', 'store_manager']))
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="deleteCategory({{ $category->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-tags fa-3x mb-3"></i>
                                        <p>Tidak ada kategori ditemukan</p>
                                        @if(in_array(Auth::user()->role, ['admin', 'store_manager']))
                                        <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i> Buat Kategori
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($categories) && $categories->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
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

// Search functionality
document.getElementById('searchCategories').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const categoryName = row.cells[1]?.textContent.toLowerCase() || '';
        const categoryDesc = row.cells[2]?.textContent.toLowerCase() || '';
        
        if (categoryName.includes(searchTerm) || categoryDesc.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection
