<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total semua produk
        $totalProduk = Product::count();

        // Jumlah produk dengan stok rendah (misalnya stok <= 5)
        $lowStockItems = Product::where('stok', '<=', 5)->count();

        // Total penjualan hari ini (jumlah harga total)
        $todaySales = Sale::whereDate('tanggal', Carbon::today())
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->sum(DB::raw('sale_details.jumlah * products.harga_jual'));

        // Pendapatan bulan ini
        $monthlyRevenue = Sale::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->sum(DB::raw('sale_details.jumlah * products.harga_jual'));

        // 5 penjualan terbaru
        $latestSales = Sale::with(['user', 'saleDetails.product'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Produk stok rendah untuk alert
        $lowStockProducts = Product::where('stok', '<=', 5)->get();

        // Produk paling sering terjual
        $bestSellingProducts = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->select('products.nama_produk', DB::raw('SUM(sale_details.jumlah) as total_terjual'))
            ->groupBy('products.id', 'products.nama_produk')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        // Produk paling cepat habis (30 hari terakhir)
        $fastDepletingProducts = DB::table('sale_details')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->where('sales.tanggal', '>=', now()->subDays(30)) // 30 hari terakhir
            ->select(
                'products.nama_produk',
                DB::raw('SUM(sale_details.jumlah) as total_terjual'),
                'products.stok',
                DB::raw('ROUND(SUM(sale_details.jumlah) / (SUM(sale_details.jumlah) + products.stok), 2) as kecepatan_habis')
            )
            ->groupBy('products.id', 'products.nama_produk', 'products.stok')
            ->orderByDesc('kecepatan_habis')
            ->take(5)
            ->get();

        // Pilih view sesuai role
        if (Auth::user()->role === 'admin') {
            return view('admin.index', compact(
                'totalProduk',
                'lowStockItems',
                'todaySales',
                'monthlyRevenue',
                'latestSales',
                'lowStockProducts',
                'bestSellingProducts',
                'fastDepletingProducts'
            ));
        } else {
            return view('dashboard.index', compact(
                'totalProduk',
                'lowStockItems',
                'todaySales',
                'monthlyRevenue',
                'latestSales',
                'lowStockProducts',
                'bestSellingProducts',
                'fastDepletingProducts'
            ));
        }
    }
}
