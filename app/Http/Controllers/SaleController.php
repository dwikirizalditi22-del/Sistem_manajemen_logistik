<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ⬅ ini yang bikin DB::transaction() bisa dipakai
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('saleDetails', 'customer')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        // Ambil semua kategori beserta produknya
        $categories = Category::with('products')->get();

        return view('sales.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'items'     => 'required|array',
        ]);

        $sale = Sale::create([
            'tanggal' => $request->sale_date,
            'user_id' => Auth::id(),
        ]);

        foreach ($request->items as $productId => $item) {
            $jumlah = isset($item['jumlah']) ? (int) $item['jumlah'] : 0;

            if ($jumlah > 0) {
                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $productId,
                    'jumlah'     => $jumlah,
                ]);

                $product = Product::find($productId);
                if ($product) {
                    $product->stok -= $jumlah;
                    $product->save();
                }
            }
        }

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil disimpan!');
    }

    public function show($id)
    {
        $sale = Sale::with(['saleDetails.product'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

     // Tampilkan halaman edit — pastikan mengirim $products
     public function edit($id)
     {
         $sale = Sale::with('saleDetails.product')->findOrFail($id);
         $products = Product::orderBy('nama_produk')->get(); // <-- kirim ini ke view
         return view('sales.edit', compact('sale', 'products'));
     }
 
     // Proses update
     public function update(Request $request, $id)
     {
         $request->validate([
             'tanggal' => 'required|date',
             'details' => 'required|array|min:1',
             'details.*.product_id' => 'required|exists:products,id',
             'details.*.jumlah' => 'required|integer|min:1',
         ]);
 
         $sale = Sale::with('saleDetails')->findOrFail($id);
 
         try {
             DB::transaction(function () use ($request, $sale) {
                 // 1) Kembalikan stok dari detail lama
                 foreach ($sale->saleDetails as $old) {
                     $prod = Product::find($old->product_id);
                     if ($prod) {
                         $prod->stok = $prod->stok + $old->jumlah;
                         $prod->save();
                     }
                 }
 
                 // 2) Hapus detail lama
                 $sale->saleDetails()->delete();
 
                 // 3) Update data sale
                 $sale->tanggal = $request->tanggal;
                 $sale->save();
 
                 // 4) Simpan detail baru dan kurangi stok
                 foreach ($request->details as $det) {
                     $product = Product::find($det['product_id']);
                     $qty = (int) $det['jumlah'];
 
                     if (!$product) {
                         throw new \Exception("Produk tidak ditemukan.");
                     }
 
                     if ($product->stok < $qty) {
                         throw new \Exception("Stok tidak cukup untuk produk: {$product->nama_produk}. Stok saat ini: {$product->stok}");
                     }
 
                     // buat sale detail baru lewat relasi
                     $sale->saleDetails()->create([
                         'product_id' => $product->id,
                         'jumlah' => $qty,
                     ]);
 
                     // kurangi stok
                     $product->stok -= $qty;
                     $product->save();
                 }
             });
         } catch (\Exception $e) {
             return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
         }
 
         return redirect()->route('sales.show', $sale->id)->with('success', 'Penjualan berhasil diperbarui.');
        }

    

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus!');
    }
}
