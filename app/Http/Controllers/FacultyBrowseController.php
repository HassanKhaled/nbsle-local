<?php


namespace App\Http\Controllers;


use App\Models\departments;
use App\Models\dept_fac;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\UniLabs;
use App\Models\universitys;
use Illuminate\Support\Facades\DB;
use function Sodium\add;

class FacultyBrowseController extends \Illuminate\Routing\Controller
{
    // Gets ALL Labs In A Faculty
    public function index($uni_id,$uniname,$facID,$facName)
    {
        $depts = dept_fac::where('uni_id',$uni_id)->where('fac_id','=',$facID)->pluck('dept_id');
        $deptnames=departments::whereIn('id',$depts)->get();
        $labs = labs::where('uni_id',$uni_id)->where('fac_id',$facID);
        $labss = $labs->get();
        $departments = array_combine($depts->toArray(),$deptnames->toArray());
        $uniimg = universitys::where('id',$uni_id)->pluck('ImagePath')->first();
        $facimg = fac_uni::where('fac_id',$facID)->where('uni_id',$uni_id)->pluck('ImagePath')->first();
        return view('templ/facbrowse', compact('uni_id','uniname','facName','facID','departments', 'labs', 'labss','uniimg','facimg'));
    }

    //  Gets All Central Labs In A University
    public function centralLabs($uni_id,$uniname){
        $labs = UniLabs::where('uni_id',$uni_id);
        $labss = $labs->get();
        $facName = 'Central Labs';
        return view('templ/CentralLabBrowse', compact('uni_id','uniname','facName','labs', 'labss'));
    }
}
