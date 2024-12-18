<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\fac_uni;
use App\Models\UniLabs;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UniBrowserController extends Controller
{
    // Browse faculties in the selected university
    public function index($uni_selected,$uniname)
    {
        $faculties = fac_uni::where('uni_id',$uni_selected)->get();
        return view('templ/unibrowse',compact('faculties','uniname','uni_selected'));
    }
}
