<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class templates implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * @var string
     */
    private $temp;

    /**
     * @var array
     */
    private $DevArray;

    /**
     * @var array
     */
    private $LabArray;

    /**
     * @var array
     */
    private $ServiceArray;

    public function __construct(string $template)
    {
        $this->temp = $template;

        // Devices headers
        $this->DevArray = [[
            'name', 'Arabic Name', 'Image Name with extension', 'model', 'manufacturer',
            'department name', 'lab name', 'num_units',
            'services (separate with comma)', 'الخدمات(فصل بينهم بفصلة)',
            'costs (separate with comma)', 'سعر الخدمات (فصل بينهم بفصلة)',
            'Available/Maintenance', 'description', 'ArabicDescription',
            'Device price', 'AdditionalInfo', 'ArabicAddInfo',
            'ManufactureYear', 'MaintenanceContract', 'ManufactureCountry', 'ManufactureWebsite'
        ]];

        // Labs headers
        $this->LabArray = [[
            'name', 'Arabic Name', 'department name', 'accredited?(y/n)', 'accredited_date', 'location',
            'coordinator 1 name', 'coordinator 1 phone', 'coordinator 1 email', '1 Staff? (y/n)',
            'coordinator 2 name', 'coordinator 2 phone', 'coordinator 2 email', '2 Staff? (y/n)'
        ]];

        // Services headers
        $this->ServiceArray = [[
            'device_name (optional)', 'service_name', 'cost', 'service_arabic', 'cost_arabic', 'desc_service'
        ]];
    }

    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        if ($this->temp === 'labs') {
            return $this->LabArray;
        } elseif ($this->temp === 'services') {
            return $this->ServiceArray;
        } else {
            return $this->DevArray;
        }
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->temp === 'labs') {
            $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
            $sheet->getStyle('G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
            $sheet->getStyle('H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
            $sheet->getStyle('J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
        } elseif ($this->temp === 'devices') {
            $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
            $sheet->getStyle('C1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
            $sheet->getStyle('G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
            $sheet->getStyle('H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
            $sheet->getStyle('M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
        } elseif ($this->temp === 'services') {
            $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ff0000');
        }
    }
}
