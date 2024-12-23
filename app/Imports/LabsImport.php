<?php

namespace App\Imports;

use App\Models\departments;
use App\Models\dept_fac;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\LabStaff;
use App\Models\Staff;
use App\Models\UniLabs;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class LabsImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithEvents
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

    public function model(array $row)
    {
        if(!$this->hasRows){
            return null;
        }
        try{
            $lab = $this->fac_id =='central' ?
                UniLabs::create([
                    'name'=> $row['name'],
                    'Arabicname'=>$row['arabic_name'],
                    'uni_id'=>Auth()->user()->uni_id,
                    'fac_id'=>$this->fac_id,
                    //'services'=>$row['services'],
                    'location'=>$row['location']])
                :
                labs::create([
                    'name'=> $row['name'],
                    'Arabicname'=>$row['arabic_name'],
                    'uni_id'=>Auth()->user()->uni_id,
                    'fac_id'=>$this->fac_id,
                    'dept_id'=>departments::where('name',$row['department_name'])->first()==null?null:departments::where('name',$row['department_name'])->first()->id,
                    //'services'=>$row['services'],
                    'accredited'=>$row['accreditedyn']=='y'?'1':'0',
                    'accredited_date'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['accredited_date']),]);
            if ($row['coordinator_1_name'] != null) {
                $staff = Staff::create(['name' => $row['coordinator_1_name'], 'telephone' => '0'.$row['coordinator_1_phone'], 'email' => $row['coordinator_1_email'], 'staff' => $row['1_staff_yn']=='y'?'1':'0']);
                $lab_staff = LabStaff::create(['lab_id' => $lab->id, 'manager_id' => $staff->id, 'central' => $this->fac_id == 'central' ? '1' : '0']);
            }
            if ($row['coordinator_2_name'] != null)
            {
                $staff = Staff::create(['name'=>$row['coordinator_2_name'],'telephone'=>'0'.$row['coordinator_2_phone'],'email'=>$row['coordinator_2_email'],'staff'=>$row['2_staff_yn']=='y'?'1':'0']);
                $lab_staff = LabStaff::create(['lab_id'=>$lab->id,'manager_id'=>$staff->id,'central'=>$this->fac_id=='central'?'1':'0']);
            }

            return $lab;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
