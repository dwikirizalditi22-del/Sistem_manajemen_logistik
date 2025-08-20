<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseDetail;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
    // Ambil data penjualan urut terbaru
    $query = Sale::orderBy('tanggal', 'desc');

    // Filter tanggal jika ada
    if ($request->filled('start_date')) {
        $query->whereDate('tanggal', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('tanggal', '<=', $request->end_date);
    }

    $sales = $query->get();

    return view('reports.index', compact('sales'));
    }
    public function productsWithoutPrice()
{
    $products = \DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
        ->select(
            'products.kode_produk',
            'products.nama_produk',
            'categories.nama_kategori',
            'suppliers.nama_supplier'
        )
        ->get();

    return view('reports.index', compact('products'));
}
    public function sales(Request $request)
    {
        $query = Sale::with(['customer', 'saleDetails'])
            ->orderBy('tanggal', 'desc');

        // Filter tanggal opsional
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $sales = $query->get()
            ->groupBy(function ($sale) {
                return \Carbon\Carbon::parse($sale->tanggal)->format('F Y'); // contoh: Januari 2025
            });

        return view('reports.sales', compact('sales'));
    }
    

}
