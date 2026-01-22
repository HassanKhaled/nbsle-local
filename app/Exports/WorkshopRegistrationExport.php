<?php

namespace App\Exports;

use App\Models\workReg;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkshopRegistrationExport implements FromCollection, WithHeadings
{
     public function collection()
    {
        return workReg::with(['workshop', 'university', 'faculty'])
            ->select(
                'id',
                'workshop_id',
                'full_name',
                'gender',
                'email',
                'national_id',
                'phone',
                'par_type',
                'par_sub_type',
                'uni_id',
                'fac_id',
                'created_at'
            )
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'ID'             => $item->id,
                    'Workshop ID'    => $item->workshop_id,
                    'Full Name'      => $item->full_name,
                    'Gender'         => $item->gender,
                    'Email'          => $item->email,
                    'National ID'    => $item->national_id,
                    'Phone'          => $item->phone,
                    'Type'           => $item->par_type,
                    'Sub Type'       => $item->par_sub_type,
                    'University'       => optional($item->university)->name,
                     'Faculty'          => optional($item->faculty)->name,
                    'Registered At'  => $item->created_at,
                ];
            });
    }


   public function headings(): array
    {
        return [
            'ID',
            'Workshop ID',
            'Full Name',
            'Gender',
            'Email',
            'National ID',
            'Phone',
            'Participant Type',
            'Sub Type',
            'University ID',
            'Faculty ID',
            'Registration Date',
        ];
    }

}
