<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReseller extends Model
{
    use HasFactory;

    protected $table = 'users_reseller';
    protected $fillable = [
        'kode_reseller',
        'name',
        'email',
        'address',
        'phone_number',
        'sales_id', // Tambahkan sales_id
    ];

    // Relasi ke tabel users_sales
    public function sales()
    {
        return $this->belongsTo(UserSales::class, 'sales_id');
    }
}
