<?php
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\DeptFacController;
use App\Http\Controllers\DeviceLabController;
use App\Http\Controllers\ExpAndImpController;
use App\Http\Controllers\FacultyBrowseController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UniBrowserController;
use App\Http\Controllers\UniversityDevicesController;
use App\Http\Controllers\UniversityLabsController;
use App\Http\Controllers\loggedHomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacUniController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\ReservationController;
 use App\Http\Controllers\NewsController;
use App\Models\User;
use App\Http\Controllers\DeviceRatingController;
use App\Http\Controllers\Reportcontroller;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/contact', function () {return view('templ/contact');});
//Route::get('/c', function () {return view('templ/index');});

Route::get('/about',function (){return view('templ/about');});
Route::get('/services',function (){return view('templ/services');});
Route::get('/vm',function (){return view('templ/visionNDmission');});
Route::get('/obj',function (){return view('templ/objective');});
Route::get('/strategy',function (){return view('templ/strategy');});

///////////// Homepage Statistics
///  number of universities - institutes - all labs  - all devices
Route::get('/', function () {
/*
    $user = Auth()->user();
    if (auth()->user()->hasRole('university')){
    //if ($user->role_id == 2){  
        //$faculties = \App\Models\fac_uni::where('uni_id',$user->uni_id)->get();
        //$labs = \App\Models\labs::where('uni_id',$user->uni_id)->pluck('id');
       // $central_labs = \App\Models\UniLabs::where('uni_id',$user->uni_id)->get();
      // $central_devices =\App\Models\UniDevices::where('uni_id',$user->uni_id)->get();
      
        $faculties = \App\Models\fac_uni::where('uni_id',$user->uni_id)->count();
        $labs = \App\Models\labs::where('uni_id',$user->uni_id)->pluck('id')->count();
        $devices = \App\Models\devices::whereIn('lab_id',$labs)->count();
        $central_labs = \App\Models\UniLabs::where('uni_id',$user->uni_id)->count();
        $central_devices =\App\Models\UniDevices::where('uni_id',$user->uni_id)->count();
        // count all units
       // $num_central_units = $central_devices->sum('num_units');
      //  $num_units = $devices->sum('num_units');
       // return compact('user','faculties','labs','devices','central_labs','central_devices','num_central_units','num_units');

       return view('templ/index_univ',compact('faculties','labs','central_labs','devices','central_devices'));



    }   
*/
 
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    $uniqueUnis = \App\Models\labs::select('uni_id')->distinct()->get();
    $universitys = \App\Models\universitys::whereIn('id',$uniqueUnis)->where('type','!=','Institution')->count();
    $institutes =\App\Models\universitys::where('type','Institution')->count() ;
    $labs = \App\Models\labs::all()->count()+\App\Models\UniLabs::all()->count();
    $devices = \App\Models\devices::all()->count()+ \App\Models\UniDevices::all()->count();
//    $devices = \App\Models\devices::sum('num_units')+ \App\Models\UniDevices::sum('num_units');
    $news = \App\Models\News::get();
    \App\Models\Visit::create([
        'page' => 'home',
        'ip'   => request()->ip(),
    ]);
    $visitsCount = \App\Models\Visit::where('page', 'home')->count();
    return view('templ/index',compact('universitys','institutes','labs','devices','news','visitsCount'));

})->name('homepage');
Route::get('/home',function (){
    $uniqueUnis = \App\Models\labs::select('uni_id')->distinct()->get();
    $universitys = \App\Models\universitys::whereIn('id',$uniqueUnis)->where('type','!=','Institution')->count();
    $institutes =\App\Models\universitys::where('type','Institution')->count() ;
    $labs = \App\Models\labs::all()->count()+\App\Models\UniLabs::all()->count();
    $devices = \App\Models\devices::all()->count()+ \App\Models\UniDevices::all()->count();
    $news = \App\Models\News::get();
      \App\Models\Visit::create([
        'page' => 'home',
        'ip'   => request()->ip(),
    ]);
    $visitsCount = \App\Models\Visit::where('page', 'home')->count();
    
//    $devices = \App\Models\devices::sum('num_units')+ \App\Models\UniDevices::sum('num_units');
    return view('templ/index',compact('universitys','institutes','labs','devices','news','visitsCount'));
})->name('home');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///
Route::get('/browse', [BrowseController::class, '__invoke'])->name('browse'); // browse universities
Route::get('/institutions', [BrowseController::class, 'getInstitutions'])->name('institutions'); // browse institutes
Route::get('/browse/{type}', [BrowseController::class, 'search'])->name('university.search'); //homepage search results
Route::get('/unibrowse/{id}/{uniname}', [UniBrowserController::class,'index']) ->name('browseuniversity'); // browse faculties in a uni
Route::get('/facbrowse/{uni_id}/{uniname}/{facID}/{facName}',[FacultyBrowseController::class,'index']) ->name('browsefaculty'); // browse labs in a faculty
Route::get('/facbrowse/{uni_id}/{uniname}',[FacultyBrowseController::class,'centralLabs']) ->name('browsecentrallab'); // browse central labs in a uni

Route::get('/device/{dev_id}/{lab_id}/{central}/{uni_id}/{uniname}/{facID?}/{facName?}',[DeviceLabController::class,'getDevice']) ->name('browsedevice');
Route::get('/news/public/details/{id}', [NewsController::class, 'publicDetails'])->name('news.public.details');
Route::post('/news/{id}/like', [NewsController::class, 'addLike'])->name('news.like');
// all devices
Route::get('/all-devices', [DeviceLabController::class, 'getAllDevices'])->name('allDevices');


Auth::routes(['register' => true]);
Auth::routes();
Route::get('logout', function ()
{
    auth()->logout();
    Session()->flush();
    return Redirect::to('/login');
})->name('logout');

Route::get('/indexHomepage',[loggedHomeController::class,'index_homepage'])->middleware('auth');

Route::get('/uniHome',[loggedHomeController::class,'index'])->middleware('auth')->name('uniHome');
Route::get('/getLabDevices',[loggedHomeController::class,'getLabDevices'])->name('getLabDevices');
Route::get('/getUniDevices',[loggedHomeController::class,'getUniDevices'])->name('getUniDevices');
Route::get('/getFacDevices',[loggedHomeController::class,'getFacDevices'])->name('getFacDevices');
Route::get('/getDeptDevices',[loggedHomeController::class,'getDeptDevices'])->name('getDeptDevices');

Route::any('/changepass',function(){    return view('Users.editpass');  })->name('ch-pass');
Route::any('/pass',[UserController::class,'updatePass'])->name('change-pass');
Route::any('/info',[UserController::class,'updatePInfo'])->name('change-info');
//Route::any('/viewuser',[UserController::class,'showUserInfo'])->name('viewUser');
Route::get('/getUser/{id}',[loggedHomeController::class,'getThisUser']);

Route::any('/import',function(){
    abort_unless(Gate::allows('import') , 403);return view('loggedTemp/import');
})->middleware('auth')->name('import');
Route::any('/export',[ExpAndImpController::class,'viewExport'])->middleware('auth')->name('export');
Route::any('/generateSheet',[ExpAndImpController::class,'generateSheet'])->name('generateSheet');
Route::any('/exporttoExcel/{what}',[ExpAndImpController::class,'exporttoExcel'])->name('exporttoExcel');
Route::any('/downloadTemplate/{labs}',[ExpAndImpController::class,'downloadTemplate'])->name('downloadTemplate');
Route::any('/importthat/{item}',[ExpAndImpController::class,'import'])->name('importthat');

Route::group(['middleware' => ['auth', 'role:admin|university']], function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('Users', UserController::class);
    Route::resource('Universitys', UniversityController::class);
    Route::resource('FacUni',FacUniController::class);
    Route::resource('DeptFac',DeptFacController::class);
    Route::resource('Lab',LabController::class);
    Route::resource('DeviceLab',DeviceLabController::class);
    Route::resource('UniLab',UniversityLabsController::class);
    Route::resource('UniDevice',UniversityDevicesController::class);

    // Booking (Reservation) Devices ==> User

    Route::get('/booking/{dev_id}/{lab_id}/{central}/{uni_id}/{uniname}/{facID?}/{facName?}',[ReservationController::class,'getReservation'])->name('reservation');
    //Route::resource('/booking',ReservationController::class);
    Route::post('/booking/store',[ReservationController::class,'store']);

   // Route::get('/userReservation/{dev_id}/{lab_id}/{central}/{uni_id}/{uniname}/{facID?}/{facName?}',[ReservationController::class,'userReservation'])->name('user-reservations');
   Route::get('/userReservation',[ReservationController::class,'userReservation'])->name('user-reservations');
   Route::delete('/userReservation/{id}',[ReservationController::class,'destroy']);
   Route::get('/userReservation/edit/{id}', [ReservationController::class, 'edit']);
   Route::post('/userReservation/update/{id}', [ReservationController::class, 'update']);

    Route::get('/ratings/create', [DeviceRatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings', [DeviceRatingController::class, 'store'])->name('ratings.store');
    Route::put('/ratings/{rating}', [DeviceRatingController::class, 'update'])->name('ratings.update');
//edit
//News 
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
Route::post('/news', [NewsController::class, 'store'])->name('news.store');
Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
Route::delete('/news-images/{id}', [NewsController::class, 'destroyImage'])->name('newsImages.destroy');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');    

   // Reservation Of Admin Faculty
   Route::get('/adminReservation',[ReservationController::class,'adminReservation'])->name('admin-reservations');
   Route::post('/adminReservation/{id}/confirm', [ReservationController::class, 'confirm'])->name('confirm');

});
