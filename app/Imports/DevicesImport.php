<?php

namespace App\Imports;

use App\Models\departments;
use App\Models\dept_fac;
use App\Models\devices;
use App\Models\labs;
use App\Models\services;
use App\Models\UniDevices;
use App\Models\UniLabs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class DevicesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithEvents
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

    public function collection(Collection $rows)
    {
        if (!$this->hasRows) {
            return null;
        }

        try {
            // get department IDs
            if ($this->fac_id != 'central') {
                if (Auth()->user()->hasRole('university')) {
                    $dept_ids = dept_fac::where('uni_id', Auth()->user()->uni_id)
                        ->where('fac_id', $this->fac_id)
                        ->join('departments', 'dept_fac.dept_id', '=', 'departments.id')
                        ->pluck('departments.id', 'departments.name');
                } else {
                    $dept_ids = dept_fac::where('uni_id', Auth()->user()->uni_id)
                        ->where('fac_id', Auth()->user()->fac_id)
                        ->join('departments', 'dept_fac.dept_id', '=', 'departments.id')
                        ->pluck('departments.id', 'departments.name');
                }
            }

            foreach ($rows as $row) {
                if ($row->filter()->isEmpty()) continue;

                // Get lab_id safely
                if ($this->fac_id == 'central') {
                    $lab_id = UniLabs::where('name', $row['lab_name'])
                        ->where('uni_id', auth()->user()->uni_id)
                        ->value('id');
                } else {
                    $dept_id = isset($dept_ids[$row['department_name']]) ? $dept_ids[$row['department_name']] : null;

                    $labQuery = labs::where('name', $row['lab_name'])
                        ->where('fac_id', $this->fac_id);

                    if ($dept_id) {
                        $labQuery->where('dept_id', $dept_id);
                    } else {
                        $labQuery->where(function ($query) {
                            $query->whereNull('dept_id')->orWhere('dept_id', 0);
                        });
                    }

                    $lab_id = $labQuery->value('id');
                }

                // Build common data array
                $data = [
                    'name' => $row['name'],
                    'Arabicname' => $row['arabic_name'],
                    'ImagePath' => 'images/universities/' . auth()->user()->uni_id . '/' . $row['image_name_with_extension'],
                    'model' => $row['model'],
                    'manufacturer' => $row['manufacturer'],
                    'lab_id' => $lab_id,
                    'num_units' => $row['num_units'],
                    'services' => $row['services_separate_with_comma'],
                    'servicesArabic' => $row['alkhdmatfsl_bynhm_bfsl'],
                    'cost' => $row['costs_separate_with_comma'],
                    'costArabic' => $row['saar_alkhdmat_fsl_bynhm_bfsl'],
                    'state' => strtolower($row['availablemaintenance']),
                    'description' => $row['description'],
                    'ArabicDescription' => $row['arabicdescription'],
                    'price' => $row['device_price'],
                    'AdditionalInfo' => $row['additionalinfo'],
                    'ArabicAddInfo' => $row['arabicaddinfo'],
                    'ManufactureYear' => $row['manufactureyear'],
                    'MaintenanceContract' => $row['maintenancecontract'],
                    'ManufactureCountry' => $row['manufacturecountry'],
                    'ManufactureWebsite' => $row['manufacturewebsite'],
                    'entry_date' => now(),
                ];

                // Check if device exists (by name + model + lab)
                if ($this->fac_id == 'central') {
                    $device = UniDevices::where('uni_id', auth()->user()->uni_id)
                        ->where('name', $row['name'])
                        ->where('model', $row['model'])
                        ->where('lab_id', $lab_id)
                        ->first();
                } else {
                    $device = devices::where('name', $row['name'])
                        ->where('model', $row['model'])
                        ->where('lab_id', $lab_id)
                        ->first();
                }

                // Update or create
                if ($device) {
                    $device->update($data);
                } else {
                    if ($this->fac_id == 'central') {
                        $data['uni_id'] = auth()->user()->uni_id;
                        $device = UniDevices::create($data);
                    } else {
                        $device = devices::create($data);
                    }
                }

                // --- Sync Services ---
                $cost = explode(',', $row['costs_separate_with_comma']);
                $servicesList = explode(',', $row['services_separate_with_comma']);
                $costArabic = explode(',', $row['saar_alkhdmat_fsl_bynhm_bfsl']);
                $servicesArabic = explode(',', $row['alkhdmatfsl_bynhm_bfsl']);

                // normalize array lengths
                while (count($servicesList) > count($cost)) array_push($cost, '');
                while (count($servicesArabic) > count($costArabic)) array_push($costArabic, '');
                while (count($servicesList) > count($servicesArabic)) {
                    array_push($servicesArabic, '');
                    array_push($costArabic, '');
                }

                // delete old services before re-adding
                services::where('device_id', $device->id)->delete();

                foreach ($servicesList as $key => $value) {
                    if (trim($value) != '') {
                        services::create([
                            'device_id' => $device->id,
                            'service_name' => $value,
                            'cost' => $cost[$key],
                            'service_arabic' => $servicesArabic[$key],
                            'cost_arabic' => $costArabic[$key],
                            'central' => $this->fac_id == 'central' ? 1 : 0,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
