<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_penjualan',
        'customer_id',
        'tanggal',
        'total_harga',
        'user_id',
        'status',
    ];
    

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}