<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocReadiness extends Model
{
    use HasFactory;

    protected $table = 'doc_readiness';
    protected $fillable = [
        'vendor_id',
        'vendor_name',
        'document_title',
        'document_number',
        'po_list_number',
        'revision',
        'tag_number',
        'fase',
        'discipline', // ✅ ini penting
        'file_path',
        'notes',
        'transmittal_number',
        'status',
    ];
}

