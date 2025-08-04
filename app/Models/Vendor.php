<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    // Tambahkan kolom yang boleh diisi melalui mass assignment
    protected $fillable = [
        'name',
        'address',
        'email',
        'telephone',
        'pic_name',
    ];
}
