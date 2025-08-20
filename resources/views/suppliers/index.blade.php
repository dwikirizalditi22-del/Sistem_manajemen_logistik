@extends('layouts.app')

@section('title', 'Daftar Pemasok')
@section('page-title', 'Daftar Suppliers')

@section('content')

{{-- Tombol Tambah Pemasok hanya untuk admin & store_manager --}}
@if(Auth::user()->role === 'admin' || Auth::user()->role === 'store_manager')
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">Tambah Pemasok</a>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Email</th>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'store_manager')
                <th>Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->nama_supplier }}</td>
                <td>{{ $supplier->alamat }}</td>
                <td>{{ $supplier->no_telepon }}</td>
                <td>{{ $supplier->email }}</td>
                
                {{-- Aksi hanya untuk admin & store_manager --}}
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'store_manager')
                <td>
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pemasok ini?')">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada pemasok.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
