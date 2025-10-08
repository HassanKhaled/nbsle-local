<?php

namespace App\Exports;

use App\Models\universitys;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UniversityRankExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
      protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'University ID',
            'University Name',
            'Average Quality Index',
            'Rank'
        ];
    }
}
