<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhoUsController extends Controller
{
    /*** Display a listing of the resource. */
    public function index($what)
    {
        return view('about',['showthis'=>$what]);
    }


}
