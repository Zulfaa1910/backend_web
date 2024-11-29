<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSales extends Model
{
    use HasFactory;

    protected $table = 'users_sales'; // Nama tabel di database
    protected $fillable = [
        'kode_sales',
        'name',
        'email',
        'password',
        'merk_hp',
        'address',
        'phone_number',
        'tanggal_lahir',
        'gender',
        'status',
    ];
}
