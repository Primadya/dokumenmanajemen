<?php

namespace App\Imports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VendorImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['name'])) return null;

        $existing = Vendor::where('name', trim($row['name']))->first();

        if ($existing) {
            $existing->update([
                'address' => $row['address'] ?? $existing->address,
                'email' => $row['email'] ?? $existing->email,
                'telephone' => $row['telephone'] ?? $existing->telephone,
                'pic_name' => $row['pic_name'] ?? $existing->pic_name,
            ]);
            return null;
        }

        return new Vendor([
            'name' => trim($row['name']),
            'address' => $row['address'] ?? '',
            'email' => $row['email'] ?? '',
            'telephone' => $row['telephone'] ?? '',
            'pic_name' => $row['pic_name'] ?? '',
        ]);
    }
}
