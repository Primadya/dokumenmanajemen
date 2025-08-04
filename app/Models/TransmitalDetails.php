<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransmitalDetails extends Model
{
    use HasFactory;

    protected $table = 'transmital_details';

protected $fillable = [
    'transmital_header_id',
    'document_number',
    'title',
    'vendor_name',
    'po_number',
    'revision',
    'tag_number',
    'document_path',
    'notes',
    'is_sent',
    'is_read',
    'status',
];


    public function header()
    {
        return $this->belongsTo(TransmitalHeader::class, 'transmital_header_id');
    }
}
