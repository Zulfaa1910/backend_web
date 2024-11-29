<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'task_pekerjaan'; // Specify the table name

    protected $fillable = [
        'kode_sales', 
        'tanggal', 
        'kode_reseller', 
        'keterangan', 
        'status'
    ];

}
