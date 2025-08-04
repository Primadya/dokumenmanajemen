<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransmitalHeader extends Model
{
    use HasFactory;

    protected $table = 'transmital_headers';

    protected $fillable = [
        'transmittal_number',
        'po_list_id',
        'transmittal_date',
        'from_department',
        'to_department',
        'remarks',
        'status',
    ];

    /**
     * Relasi ke TransmitalDetails (plural sesuai nama file model)
     */
    public function details()
    {
        return $this->hasMany(TransmitalDetails::class, 'transmital_header_id');
    }
}
