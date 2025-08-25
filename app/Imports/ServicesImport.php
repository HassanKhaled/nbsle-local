<?php

namespace App\Imports;

use App\Models\devices;
use App\Models\services;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class ServicesImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithEvents
{
    private $hasRows = false;
    private $fac_id;

    public function __construct($fac_id)
    {
        $this->fac_id = $fac_id;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $sheet = $event->reader->getActiveSheet();
                $highestRow = $sheet->getHighestRow();

                if ($highestRow <= 1) {
                    throw new \Exception('The file contains no rows to import.');
                }

                $this->hasRows = true;
            },
        ];
    }

    public function model(array $row)
    {
        if (!$this->hasRows) {
            return null;
        }

        // find device by name in this university
        // $device = devices::where('name', $row['device_name'])
        //     ->where('uni_id', Auth::user()->uni_id)
        //     ->first();

        $device = devices::where('name', $row['device_name'])->first();

        if (!$device) {
            // skip if device not found
            return null;
        }
        

        return services::create([
            'device_id'    => $device->id,
            'service_name' => $row['service_name'],
            'cost'         => $row['cost'],
            'service_arabic' => $row['service_arabic'],
            'cost_arabic'    => $row['cost_arabic'],
            'desc_service'   => $row['desc_service'],
            'central'        => $this->fac_id == 'central' ? 1 : 0,
        ]);
    }
}
