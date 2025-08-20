<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier', 'purchaseDetails.product')->latest()->paginate(10);
        $suppliers = Supplier::all();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('purchases.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0', // fix error unit_price
        ]);

        $purchase = new Purchase();
        $purchase->supplier_id = $request->supplier_id;
        $purchase->tanggal = $request->tanggal;
        $purchase->kode_pembelian = 'PB-' . time();
        $purchase->user_id = Auth::id();
        $purchase->status = 'pending';
        $purchase->total_harga = 0;
        $purchase->save();

        $totalHarga = 0;

        // simpan detail pembelian
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $purchase->purchaseDetails()->create([
                'product_id' => $item['product_id'],
                'jumlah' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $subtotal,
            ]);
            $totalHarga += $subtotal;
        }

        // update total harga pembelian
        $purchase->total_harga = $totalHarga;
        $purchase->save();

        return redirect()->route('purchases.index')->with('success', 'Pemesanan berhasil disimpan. Menunggu validasi Store Manager.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'purchaseDetails.product');
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        // hanya admin atau store_manager yang bisa edit
        if (!in_array(Auth::user()->role, ['admin', 'store_manager'])) {
            return redirect()->route('purchases.index')->with('error', 'Anda tidak memiliki izin untuk mengedit.');
        }

        $suppliers = Supplier::all();
        $products = Product::all();
        $purchase->load('purchaseDetails.product');

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        // hanya admin atau store_manager yang bisa update status
        if (!in_array(Auth::user()->role, ['admin', 'store_manager'])) {
            return redirect()->route('purchases.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui.');
        }

        if ($request->has('status')) {
            $purchase->status = $request->status; // completed / cancelled
            $purchase->save();

            return redirect()->route('purchases.index')->with('success', 'Status pembelian diperbarui.');
        }

        return redirect()->route('purchases.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }

    public function destroy(Purchase $purchase)
    {
        // hanya admin atau store_manager yang bisa delete
        if (!in_array(Auth::user()->role, ['admin', 'store_manager'])) {
            return redirect()->route('purchases.index')->with('error', 'Anda tidak memiliki izin untuk menghapus.');
        }

        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
