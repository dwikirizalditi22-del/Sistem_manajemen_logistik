<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\CateSupplier;
use Illuminate\Http\Request;


class ProductController extends Controller
{
   
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required',
            
            
            'stock_quantity' => 'required|numeric',
            // 'minimum_stock' => 'required|numeric',
            'description' => 'nullable|string',
            // 'is_active' => 'nullable|boolean',
        ]);

        $product = new Product();
        $product->kode_produk = 'PRD-' . strtoupper(uniqid());
        $product->nama_produk = $validated['name'];
        $product->category_id = $validated['category_id'];
        $product->satuan = $validated['unit'];
        
        $product->stok = $validated['stock_quantity'];
        // $product->minimum_stock = $validated['minimum_stock'];
        $product->deskripsi = $validated['description'] ?? null;
        // $product->is_active = $request->has('is_active');
        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama_produk' => 'required',
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'required|numeric',
            // 'minimum_stock' => 'nullable|numeric',
            
            // 'is_active' => 'nullable|boolean',
        ]);

        $product->nama_produk = $validated['nama_produk'];
        $product->category_id = $validated['category_id'];
        $product->stok = $validated['stock_quantity'];
        // $product->minimum_stock = $validated['minimum_stock'] ?? 0;
        
        // $product->is_active = $request->has('is_active') || $validated['is_active'] == 1;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }


    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    


}
