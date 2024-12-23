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
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
//use phpDocumentor\Reflection\Types\Collection;
use Illuminate\Support\Collection;


class DevicesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithEvents
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $hasRows = false;

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

    public function  __construct($fac_id)
    {
        $this->fac_id= $fac_id;
    }

    public function collection(Collection $rows)
    {
        if(!$this->hasRows){
            return null;
        }
        try{
            if ( $this->fac_id!='central') {
                if (Auth()->user()->hasRole('university')) {
                    $dept_ids = dept_fac::where('uni_id', Auth()->user()->uni_id)->where('fac_id', $this->fac_id)
                        ->join('departments', 'dept_fac.dept_id', '=', 'departments.id')->pluck('departments.id', 'departments.name');
                }
                else{
                    $dept_ids = dept_fac::where('uni_id', Auth()->user()->uni_id)->where('fac_id', Auth()->user()->fac_id)
                        ->join('departments', 'dept_fac.dept_id', '=', 'departments.id')->pluck('departments.id', 'departments.name');
                }
            }
            foreach ($rows as $row)
            {
                if ($row->filter()->isNotEmpty()) {
                    $device = $this->fac_id == 'central' ?
                        UniDevices::create([
                            'name' => $row['name'],
                            'Arabicname' => $row['arabic_name'],
                            'ImagePath' => 'images/universities/' . auth()->user()->uni_id . '/' . $row['image_name_with_extension'],
                            'model' => $row['model'],
                            'manufacturer' => $row['manufacturer'],
                            'lab_id' => UniLabs::where('name', $row['lab_name'])->where('uni_id', auth()->user()->uni_id)->pluck('id')->first(),
                            'uni_id' => auth()->user()->uni_id,
                            'num_units' => $row['num_units'],
                            'services' => $row['services_separate_with_comma'],
                            'servicesArabic' => $row['alkhdmatfsl_bynhm_bfsl'],
                            'cost' => $row['costs_separate_with_comma'],
                            'costArabic' => $row['saar_alkhdmat_fsl_bynhm_bfsl'],
                            'state' => strtolower($row['availablemaintenance']),
                            'description' => $row['description'],
                            'ArabicDescription' => $row['arabicdescription'],
                            'price'=>$row['device_price'],
                            'AdditionalInfo' => $row['additionalinfo'],
                            'ArabicAddInfo' => $row['arabicaddinfo'],
                            'ManufactureYear' => $row['manufactureyear'],
                            'MaintenanceContract' => $row['maintenancecontract'],
                            'ManufactureCountry' => $row['manufacturecountry'],
                            'ManufactureWebsite' => $row['manufacturewebsite'],
                            'entry_date' => date('Y-m-d H:i:s')])
                        :
                        devices::create([
                            'name' => $row['name'],
                            'Arabicname' => $row['arabic_name'],
                            'ImagePath' => 'images/universities/' . auth()->user()->uni_id . '/' . $row['image_name_with_extension'],
                            'model' => $row['model'],
                            'manufacturer' => $row['manufacturer'],
                            'lab_id' => $row['department_name'] == null ? labs::where('name', $row['lab_name'])->where('fac_id', $this->fac_id)->Where(function($query) {
                                $query->WhereNull('dept_id')
                                    ->orwhere('dept_id', 0);
                            })->pluck('id')->first()
                                : labs::where('name', $row['lab_name'])->where('fac_id', $this->fac_id)->where('dept_id', $dept_ids[$row['department_name']])->pluck('id')->first(),
                            'num_units' => $row['num_units'],
                            'services' => $row['services_separate_with_comma'],
                            'servicesArabic' => $row['alkhdmatfsl_bynhm_bfsl'],
                            'cost' => $row['costs_separate_with_comma'],
                            'costArabic' => $row['saar_alkhdmat_fsl_bynhm_bfsl'],
                            'state' => $row['availablemaintenance'],
                            'description' => $row['description'],
                            'ArabicDescription' => $row['arabicdescription'],
                            'price'=>$row['device_price'],
                            'AdditionalInfo' => $row['additionalinfo'],
                            'ArabicAddInfo' => $row['arabicaddinfo'],
                            'ManufactureYear' => $row['manufactureyear'],
                            'MaintenanceContract' => $row['maintenancecontract'],
                            'ManufactureCountry' => $row['manufacturecountry'],
                            'ManufactureWebsite' => $row['manufacturewebsite'],
                            'entry_date' => date('Y-m-d H:i:s')]);
                }
                $cost = explode(',',$row['costs_separate_with_comma']);
                $services = explode(',',$row['services_separate_with_comma']);
                $costArabic = explode(',',$row['saar_alkhdmat_fsl_bynhm_bfsl']);
                $servicesArabic = explode(',',$row['alkhdmatfsl_bynhm_bfsl']);

                //if service with no cost value
                while(count($services) > count($cost)) {    array_push($cost,'');   }
                //if Arabic service with no cost value
                while(count($servicesArabic) > count($costArabic)) {    array_push($costArabic,''); }
                //if service with no arabic equivalent
                while(count($services) > count($servicesArabic)){
                    array_push($servicesArabic,'');
                    array_push($costArabic,'');
                }
                foreach ($services as $key=>$value){
                    $service = $this->fac_id =='central'?
                        services::create(['device_id'=>$device->id, 'service_name'=>$value, 'cost'=>$cost[$key],
                            'service_arabic'=>$servicesArabic[$key], 'cost_arabic'=>$costArabic[$key], 'central'=>1])
                        :
                        services::create(['device_id'=>$device->id, 'service_name'=>$value, 'cost'=>$cost[$key],
                            'service_arabic'=>$servicesArabic[$key], 'cost_arabic'=>$costArabic[$key], 'central'=>0]);
                }
            }
        } catch(\Exception $e) {
            throw $e;
        }
    }
}
