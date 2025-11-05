<?php

namespace App\Http\Controllers;
use App\Models\devices;
use App\Models\fac_uni;
use App\Models\facultys;
use App\Models\labs;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use Hamcrest\Description;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Exports\UniversityRankExport;
use Maatwebsite\Excel\Facades\Excel;
class Reportcontroller extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 2) {
            $universityId = $user->uni_id;  
            $facultyId    = $request->input('faculty_id', 'all');
            $universities = universitys::where('id', $user->uni_id)->get();
        } else {
            $universityId = $request->input('university_id', 'all');
            $facultyId    = $request->input('faculty_id', 'all');
            $universities = universitys::all();
          
        }
        $faculties = $universityId !== 'all'
                    ? fac_uni::where('uni_id', $universityId)->get()
                    : fac_uni::all();

            if ($universityId === 'all' && $facultyId === 'all') {
                $labsQuery = UniLabs::query()
                    ->withCount([
                        'devices as devices_count',
                        'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                        'devices as devices_with_image_count' => fn($q) =>
                            $q->whereNotNull('ImagePath')
                            ->where('ImagePath', '!=', '')
                            ->where('ImagePath', 'not like', '%No_Image.png%'),
                        'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                        'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                        'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                        'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                        'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                        'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                        'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                        'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->whereIn('MaintenanceContract', [0, 1]),
                        'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                        'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),
                        'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                    ])
                    ->withMax('devices as last_entry_date', 'entry_date');

                $labsQuery2 = Labs::query()
                    ->withCount([
                        'devices as devices_count',
                        'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                        'devices as devices_with_image_count' => fn($q) =>
                            $q->whereNotNull('ImagePath')
                            ->where('ImagePath', '!=', '')
                            ->where('ImagePath', 'not like', '%No_Image.png%'),
                        'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                        'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                        'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                        'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                        'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                        'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                        'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                        'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->whereIn('MaintenanceContract', [0, 1]),
                        'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                        'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),
                        'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                    ])
                    ->withMax('devices as last_entry_date', 'entry_date');

                // ندمج النتائج
                $labs = $labsQuery->get()->merge($labsQuery2->get());

            }
            else if ($universityId!=="all" && $faculties->isEmpty()) {
                // مفيش كليات، نبحث في UniLabs
                $labsQuery = UniLabs::query()->where('uni_id', $universityId);

                    $labs = $labsQuery
                        ->withCount([
                            'devices as devices_count',

                            'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                            'devices as devices_with_image_count' => fn($q) =>
                                $q->whereNotNull('ImagePath')
                                ->where('ImagePath', '!=', '')
                                ->where('ImagePath', 'not like', '%No_Image.png%'),
                            'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                            'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                            'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                            'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                            'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                            'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                            'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                            'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->whereIn('MaintenanceContract', [0, 1]),
                            'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                            'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),

                            'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                        ])
                        ->withMax('devices as last_entry_date', 'entry_date') ;
                    $facultyId = 'central';
            } 
            else if ($universityId!=="all" && $facultyId == 'all') {

                $labsQuery = Labs::query()->where('uni_id', $universityId)
                ->withCount([
                    'devices as devices_count',
                    'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                    'devices as devices_with_image_count' => fn($q) =>
                        $q->whereNotNull('ImagePath')
                        ->where('ImagePath', '!=', '')
                        ->where('ImagePath', 'not like', '%No_Image.png%'),
                    'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                    'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                    'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                    'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                    'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                    'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                    'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                    'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->whereIn('MaintenanceContract', [0, 1]),
                    'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                    'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),
                    'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                ])
                ->withMax('devices as last_entry_date', 'entry_date');

            $labsQuery2 = UniLabs::query()->where('uni_id', $universityId)
                ->withCount([
                    'devices as devices_count',
                    'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                    'devices as devices_with_image_count' => fn($q) =>
                        $q->whereNotNull('ImagePath')
                        ->where('ImagePath', '!=', '')
                        ->where('ImagePath', 'not like', '%No_Image.png%'),
                    'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                    'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                    'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                    'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                    'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                    'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                    'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                    'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->whereIn('MaintenanceContract', [0, 1]),
                    'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                    'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),
                    'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                ])
                ->withMax('devices as last_entry_date', 'entry_date');   
                $labs = $labsQuery->get()->merge($labsQuery2->get()); 
            }
           
            else if ($universityId!=="all" && $facultyId !== 'all') {
                $labsQuery = Labs::query();
                if ($universityId) {
                    $labsQuery->where('uni_id', $universityId);
                }
                if ($facultyId) {
                    $labsQuery->where('fac_id', $facultyId);
                }
                    $labs = $labsQuery
                        ->withCount([
                            'devices as devices_count',

                            'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                            'devices as devices_with_image_count' => fn($q) =>
                                $q->whereNotNull('ImagePath')
                                ->where('ImagePath', '!=', '')
                                ->where('ImagePath', 'not like', '%No_Image.png%'),
                            'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                            'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                            'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                            'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                            'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                            'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                            'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                            'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->whereIn('MaintenanceContract', [0, 1]),
                            'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                            'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),

                            'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                        ])
                        ->withMax('devices as last_entry_date', 'entry_date') 
                        ->get();
            }
            else if ($facultyId !== 'all' && $universityId === 'all') {
                $labsQuery = Labs::query();
                $labsQuery->where('fac_id', $facultyId);
                $labs = $labsQuery
                        ->withCount([
                            'devices as devices_count',

                            // حقول مطلوبة
                            'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                            'devices as devices_with_image_count' => fn($q) =>
                                $q->whereNotNull('ImagePath')
                                ->where('ImagePath', '!=', '')
                                ->where('ImagePath', 'not like', '%No_Image.png%'),
                            'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                            'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                            'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                            'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                            'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                            'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                            'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                            'devices as devices_with_maintenance_contract_count' => fn($q) =>$q->whereNotNull('MaintenanceContract')->whereIn('MaintenanceContract', [0, 1]),
                            'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                            'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),

                            // الأجهزة المتاحة
                            'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                        ])
                        ->withMax('devices as last_entry_date', 'entry_date') // آخر تاريخ دخول جهاز
                        ->get();
            }

        foreach ($labs as $lab) {
            $devicesCount = max($lab->devices_count, 1); 
            $lab->image_upload_indicator = ($lab->devices_with_image_count / $devicesCount) * 100;

            $fieldCounts = [
                $lab->devices_with_name_count,
                $lab->devices_with_image_count,
                $lab->devices_with_model_count,
                $lab->devices_with_cost_count,
                $lab->devices_with_price_count,
                $lab->devices_with_services_count,
                $lab->devices_with_description_count,
                $lab->devices_with_manufacturer_count,
                $lab->devices_with_manufacture_year_count,
                $lab->devices_with_maintenance_contract_count,
                $lab->devices_with_manufacture_country_count,
                $lab->devices_with_manufacture_website_count,
                $lab->available_devices_count, 
           ];
           $total = count($fieldCounts);
           $zeros = collect($fieldCounts)->filter(fn($value) => $value == 0)->count();

            $percentage = 100 - ($zeros / $total) * 100;
            $lab->data_completeness_full = $percentage;

                 $devices = $lab->devices;
                $pointsSum = 0;
                $devicesCount = $devices->count();
                $currentYear = now()->year;
                foreach ($devices as $device) {
                    // نحدد أي تاريخ هنستخدم
                    $date = $device->updated_at ?? $device->entry_date;
                    if ($date) {
                        $year = \Carbon\Carbon::parse($date)->year;
                        $diff = $currentYear - $year;
                        $score = max(0, 100 - ($diff * 10)); // ينقص 10 كل سنة
                        $pointsSum += $score;
                    } else {
                        $pointsSum += 0; // لا يوجد تاريخ نهائياً
                    }

                }

                $lab->kpi_update = $devicesCount > 0 ? $pointsSum / $devicesCount : 0;

                $lab->data_quality_index = (0.5 * $lab->data_completeness_full) + (0.2 * $lab->image_upload_indicator) + (0.3 * $lab->kpi_update);

                if ($lab->data_quality_index > 90) {
                    $lab->data_quality="ممتاز";
                    $lab->data_quality_description = "البيانات دقيقة، كاملة، ومحدثة باستمرار. تعكس مستوى عالٍ من الاحترافية والموثوقية.";
                    $lab->Proposed_proposal = "استدامة الجودة: الحفاظ على الآليات الحالية للتدقيق والتحديث، ومتابعة آراء المستخدمين.";

                } elseif ($lab->data_quality_index < 89 && $lab->data_quality_index >= 80) {
                    $lab->data_quality="جيد جداً";
                    $lab->data_quality_description = "البيانات جيدة بشكل عام، لكن قد توجد بعض النواقص الطفيفة في الاكتمال أو الحداثة.";
                    $lab->Proposed_proposal = "تحسين مستمر: التركيز على معالجة النواقص البسيطة، مثل إدخال الصور أو تحديث البيانات القديمة.";

                } elseif ($lab->data_quality_index < 79 && $lab->data_quality_index >= 60) {
                    $lab->data_quality="مقبول";
                    $lab->data_quality_description = "البيانات مقبولة، لكنها تحتاج إلى مجهود كبير لتحسينها. توجد ثغرات واضحة في الاكتمال أو الحداثة.";
                    $lab->Proposed_proposal = "خطة تحسين فورية: وضع خطة عمل محددة لمعالجة نقاط الضعف الرئيسية، مع توفير الموارد اللازمة.";
                } else {
                    $lab->data_quality="ضعيف";
                    $lab->data_quality_description ="البيانات غير موثوقة إلى حد كبير، وربما تكون قديمة أو غير مكتملة. لا يمكن الاعتماد عليها بشكل كامل.";
                    $lab->Proposed_proposal = "إعادة هيكلة شاملة: تتطلب الموقف تدخلاً جذرياً لإعادة جمع وتحديث البيانات من البداية، مع مراجعة شاملة لآليات الإدخال والتدقيق.";
                }
            
        }
       

      $labCount = max(count($labs), 1); // avoid division by zero
        $totalDevicesCount = max($labs->sum('devices_count'), 1);

        $stats = [
            'totalDevices' => $labs->sum('devices_count'),
            'totalLabs' => $labs->count(),
            'totalDevicesName' => ($labs->sum('devices_with_name_count') / $totalDevicesCount) * 100,
            'totalDevicesImage' => ($labs->sum('devices_with_image_count') / $totalDevicesCount) * 100,
            'totalDevicesModel' => ($labs->sum('devices_with_model_count') / $totalDevicesCount) * 100,
            'totalDevicesPrice' => ($labs->sum('devices_with_price_count') / $totalDevicesCount) * 100,
            'totalDevicesCost' => ($labs->sum('devices_with_cost_count') / $totalDevicesCount) * 100,
            'totalDevicesServices' => ($labs->sum('devices_with_services_count') / $totalDevicesCount) * 100,
            'totalDevicesDescription' => ($labs->sum('devices_with_description_count') / $totalDevicesCount) * 100,
            'totalDevicesManufacturer' => ($labs->sum('devices_with_manufacturer_count') / $totalDevicesCount) * 100,
            'totalDevicesManufactureYear' => ($labs->sum('devices_with_manufacture_year_count') / $totalDevicesCount) * 100,
            'totalDevicesMaintenanceContract' => ($labs->sum('devices_with_maintenance_contract_count') / $totalDevicesCount) * 100,
            'totalDevicesManufactureCountry' => ($labs->sum('devices_with_manufacture_country_count') / $totalDevicesCount) * 100,
            'totalDevicesManufactureWebsite' => ($labs->sum('devices_with_manufacture_website_count') / $totalDevicesCount) * 100,
            'totalAvailableDevicesCount' => ($labs->sum('available_devices_count') / $totalDevicesCount) * 100,
            'imageIndicator' => ($labs->sum('image_upload_indicator') / $labCount),
            'dataCompleteness' => ($labs->sum('data_completeness_full') / $labCount),
            'totalKPIUpdate' => ($labs->sum('kpi_update') / $labCount), 
            'totalDataQualityIndex' => (0.5 * $labs->sum('data_completeness_full') / $labCount) + (0.2 * $labs->sum('image_upload_indicator') / $labCount) + (0.3 * $labs->sum('kpi_update') / $labCount)

        ];
            if ($stats['totalDataQualityIndex'] > 90) {
                $stats['totalDataQuality']="ممتاز";
                $stats['totalDataQuality_description'] = "البيانات دقيقة، كاملة، ومحدثة باستمرار. تعكس مستوى عالٍ من الاحترافية والموثوقية.";
                $stats['Proposed_proposal'] = "استدامة الجودة: الحفاظ على الآليات الحالية للتدقيق والتحديث، ومتابعة آراء المستخدمين.";

                } elseif ($stats['totalDataQualityIndex'] < 89 && $stats['totalDataQualityIndex'] >= 80) {
                    $stats['totalDataQuality']="جيد جداً";
                    $stats['totalDataQuality_description'] = "البيانات جيدة بشكل عام، لكن قد توجد بعض النواقص الطفيفة في الاكتمال أو الحداثة.";
                    $stats['Proposed_proposal'] = "تحسين مستمر: التركيز على معالجة النواقص البسيطة، مثل إدخال الصور أو تحديث البيانات القديمة.";

                } elseif ($stats['totalDataQualityIndex'] < 79 && $stats['totalDataQualityIndex'] >= 60) {
                    $stats['totalDataQuality']="مقبول";
                    $stats['totalDataQuality_description'] = "البيانات مقبولة، لكنها تحتاج إلى مجهود كبير لتحسينها. توجد ثغرات واضحة في الاكتمال أو الحداثة.";
                    $stats['Proposed_proposal'] = "خطة تحسين فورية: وضع خطة عمل محددة لمعالجة نقاط الضعف الرئيسية، مع توفير الموارد اللازمة.";
                } else {
                    $stats['totalDataQuality']="ضعيف";
                    $stats['totalDataQuality_description'] ="البيانات غير موثوقة إلى حد كبير، وربما تكون قديمة أو غير مكتملة. لا يمكن الاعتماد عليها بشكل كامل.";
                    $stats['Proposed_proposal'] = "إعادة هيكلة شاملة: تتطلب الموقف تدخلاً جذرياً لإعادة جمع وتحديث البيانات من البداية، مع مراجعة شاملة لآليات الإدخال والتدقيق.";
                }
        return view('loggedTemp.reports', compact(
            'labs',
            'universities',
            'faculties',
            'universityId',
            'facultyId',
            'stats'
        ));
    }


    public function calculateUniversityRanks()
    {
        $universities = universitys::all();

        $allLabs = collect();

        foreach ($universities as $university) {
            $universityId = $university->id;

            $labsQuery = Labs::query()->where('uni_id', $universityId)
                ->withCount([
                    'devices as devices_count',
                    'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                    'devices as devices_with_image_count' => fn($q) =>
                        $q->whereNotNull('ImagePath')
                        ->where('ImagePath', '!=', '')
                        ->where('ImagePath', 'not like', '%No_Image.png%'),
                    'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                    'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                    'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                    'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                    'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                    'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                    'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                    'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->where('MaintenanceContract', '!=', ''),
                    'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                    'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),
                    'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                ])
                ->withMax('devices as last_entry_date', 'entry_date');

            $labsQuery2 = UniLabs::query()->where('uni_id', $universityId)
                ->withCount([
                    'devices as devices_count',
                    'devices as devices_with_name_count' => fn($q) => $q->whereNotNull('name')->where('name', '!=', ''),
                    'devices as devices_with_image_count' => fn($q) =>
                        $q->whereNotNull('ImagePath')
                        ->where('ImagePath', '!=', '')
                        ->where('ImagePath', 'not like', '%No_Image.png%'),
                    'devices as devices_with_model_count' => fn($q) => $q->whereNotNull('model')->where('model', '!=', ''),
                    'devices as devices_with_cost_count' => fn($q) => $q->whereNotNull('cost')->where('cost', '!=', ''),
                    'devices as devices_with_price_count' => fn($q) => $q->whereNotNull('price')->where('price', '!=', ''),
                    'devices as devices_with_services_count' => fn($q) => $q->whereNotNull('services')->where('services', '!=', ''),
                    'devices as devices_with_description_count' => fn($q) => $q->whereNotNull('description')->where('description', '!=', ''),
                    'devices as devices_with_manufacturer_count' => fn($q) => $q->whereNotNull('manufacturer')->where('manufacturer', '!=', ''),
                    'devices as devices_with_manufacture_year_count' => fn($q) => $q->whereNotNull('ManufactureYear')->where('ManufactureYear', '!=', ''),
                    'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->where('MaintenanceContract', '!=', ''),
                    'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                    'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),
                    'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
                ])
                ->withMax('devices as last_entry_date', 'entry_date');

            $labs = $labsQuery->get()->merge($labsQuery2->get());

            foreach ($labs as $lab) {
                $devicesCount = max($lab->devices_count, 1);
                $lab->image_upload_indicator = ($lab->devices_with_image_count / $devicesCount) * 100;

                $fieldCounts = [
                    $lab->devices_with_name_count,
                    $lab->devices_with_image_count,
                    $lab->devices_with_model_count,
                    $lab->devices_with_cost_count,
                    $lab->devices_with_price_count,
                    $lab->devices_with_services_count,
                    $lab->devices_with_description_count,
                    $lab->devices_with_manufacturer_count,
                    $lab->devices_with_manufacture_year_count,
                    $lab->devices_with_maintenance_contract_count,
                    $lab->devices_with_manufacture_country_count,
                    $lab->devices_with_manufacture_website_count,
                    $lab->available_devices_count,
                ];
           
                $total = count($fieldCounts);
                $zeros = collect($fieldCounts)->filter(fn($value) => $value == 0)->count();
                $lab->data_completeness_full = 100 - ($zeros / $total) * 100;

                $devices = $lab->devices;
                $pointsSum = 0;
                $devicesCount = $devices->count();
                $currentYear = now()->year;

                foreach ($devices as $device) {
                    $date = $device->updated_at ?? $device->entry_date;
                    if ($date) {
                        $year = \Carbon\Carbon::parse($date)->year;
                        $diff = $currentYear - $year;
                        $score = max(0, 100 - ($diff * 10));
                        $pointsSum += $score;
                    }
                }

                $lab->kpi_update = $devicesCount > 0 ? $pointsSum / $devicesCount : 0;

                $lab->data_quality_index = (0.5 * $lab->data_completeness_full)
                                        + (0.2 * $lab->image_upload_indicator)
                                        + (0.3 * $lab->kpi_update);
            }

            $averageIndex = $labs->avg('data_quality_index') ?? 0;

            $allLabs->push([
                'university_id' => $universityId,
                'university_name' => $university->name,
                'average_quality_index' => round($averageIndex, 2),
            ]);
        }

        $sortedUniversities = $allLabs->sortByDesc('average_quality_index')->values();
        $sortedUniversities = $sortedUniversities
                                ->values()
                                ->map(function ($uni, $index) {
                                    $uni['rank'] = $index + 1;
                                    return $uni;
                                });


        return Excel::download(new UniversityRankExport($sortedUniversities->toArray()), 'university_ranks.xlsx');

        // return response()->json([
        //     'university_ranks' => $sortedUniversities->values(),
        // ]);
    }

}
