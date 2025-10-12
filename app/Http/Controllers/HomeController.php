<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use App\Models\Visit;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function visits()
    {
        // تسجيل الزيارة
        Visit::create([
            'page' => 'home',
            'ip'   => request()->ip(),
        ]);

        // حساب عدد الزيارات
        $visitsCount = Visit::where('page', 'home')->count();

        return view('home', compact('visitsCount'));
    }
}
