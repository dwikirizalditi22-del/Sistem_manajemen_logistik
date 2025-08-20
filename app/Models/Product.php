<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'harga_jual',
        'stok',
        'deskripsi',
        'supplier_id',       // opsional, jika kamu ingin menambahkan deskripsi produk
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id'); // ✅ fix foreign key
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
