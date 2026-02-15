<?php

namespace App\Exports;
use App\Models\CertificateRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CertificateRequestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CertificateRequest::with('workshop')->get()->map(function ($r) {
            return [
                'Workshop name' => $r->workshop->workshop_ar_title ?? $r->workshop->workshop_en_title,
                'workshop id'  => $r->workshop->id,
                'workshop starting date' => $r->workshop->st_date,
                'Name' => $r->name,
                'Email' => $r->email,
                'Days' => is_array($r->days) ? implode(',', $r->days) : $r->days,
                'Certificates' => $r->cert_count,
                'Cost' => $r->cost,
                'Status' => $r->status,
                'Receipt' => asset('storage/' . $r->image_receipt),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Workshop name',
            'workshop id',
            'workshop starting date',
            'Name',
            'Email',
            'Days',
            'Certificates',
            'Cost',
            'Status',
            'Receipt Link',
        ];
    }
}

