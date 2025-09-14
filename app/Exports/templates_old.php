<?php

namespace App\Exports;

use App\Models\devices;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class templates implements FromArray,WithHeadings,ShouldAutoSize,WithStyles
{
    /**
     * @var \string[][]
     */
    private $DevArray;
    /**
     * @var \string[][]
     */
    private $LabArray;
    /**
     * @var string
     */
    private $temp;
    /**
    * @return \Illuminate\Support\Collection
    */
//    protected $templateArray;
    public function __construct(string $template)
    {
        $this->temp = $template;
        $this->DevArray =[['name', 'Arabic Name', 'Image Name with extension', 'model', 'manufacturer','department name', 'lab name', 'num_units',
            'services (separate with comma)', 'الخدمات(فصل بينهم بفصلة)','costs (separate with comma)', 'سعر الخدمات (فصل بينهم بفصلة)'
            ,'Available/Maintenance','description', 'ArabicDescription', 'Device price', 'AdditionalInfo', 'ArabicAddInfo',
            'ManufactureYear', 'MaintenanceContract', 'ManufactureCountry', 'ManufactureWebsite']];

        $this->LabArray = [['name ', 'Arabic Name', 'department name', 'accredited?(y/n)', 'accredited_date', 'location',
                'coordinator 1 name', 'coordinator 1 phone', 'coordinator 1 email', '1 Staff? (y/n)',
                'coordinator 2 name', 'coordinator 2 phone', 'coordinator 2 email', '2 Staff? (y/n)']];
    }

    public function array():array
    {
//        dd($this->templateArray);
        return [];
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        if($this->temp == 'labs') return $this->LabArray;

        elseif ($this->template == 'services') {
            return collect([
                ['device_name', 'service_name', 'cost', 'service_arabic', 'cost_arabic', 'desc_service'],
            ]);
        }
        
        else return $this->DevArray;
    }

    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
        if($this->temp == 'labs') {
            $sheet->getStyle('A1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
            $sheet->getStyle('G1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
            $sheet->getStyle('H1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
            $sheet->getStyle('J1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
        }
        else{
            $sheet->getStyle('A1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
            $sheet->getStyle('C1:D1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
            $sheet->getStyle('G1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
            $sheet->getStyle('H1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
            $sheet->getStyle('M1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('ff0000');
        }
    }
}
