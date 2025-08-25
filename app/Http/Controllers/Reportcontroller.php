<?php

namespace App\Http\Controllers;
use App\Models\devices;
use App\Models\fac_uni;
use App\Models\facultys;
use App\Models\labs;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use Illuminate\Http\Request;

class Reportcontroller extends Controller
{

    public function index(Request $request)
    {
        $universityId = $request->input('university_id');
        $facultyId    = $request->input('faculty_id');

        $labsQuery = labs::query();

        if ($universityId) {
            $labsQuery->where('uni_id', $universityId);
        }
        if ($facultyId) {
            $labsQuery->where('fac_id', $facultyId);
        }

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
                'devices as devices_with_maintenance_contract_count' => fn($q) => $q->whereNotNull('MaintenanceContract')->where('MaintenanceContract', '!=', ''),
                'devices as devices_with_manufacture_country_count' => fn($q) => $q->whereNotNull('ManufactureCountry')->where('ManufactureCountry', '!=', ''),
                'devices as devices_with_manufacture_website_count' => fn($q) => $q->whereNotNull('ManufactureWebsite')->where('ManufactureWebsite', '!=', ''),

                // الأجهزة المتاحة
                'devices as available_devices_count' => fn($q) => $q->where('state', 'available'),
            ])
            ->withMax('devices as last_entry_date', 'entry_date') // آخر تاريخ دخول جهاز
            ->get();

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
            $lab->kpi_update = 0; // default
            if ($lab->last_entry_date) {
                $year = \Carbon\Carbon::parse($lab->last_entry_date)->year;

                $pointsMap = [
                    2025 => 100,
                    2024 => 90,
                    2023 => 70,
                    2022 => 40,
                    2021 => 30,
                    2020 => 20,
                    2019 => 15,
                    2018 => 12,
                    2017 => 11,
                    2016 => 10,
                ];

                // لو السنة موجودة في الجدول
                if (isset($pointsMap[$year])) {
                    $lab->kpi_update = $pointsMap[$year];
                } else {
                    // أي سنة أقدم من 2016 تأخذ 5 نقاط
                    $lab->kpi_update = ($year < 2016) ? 5 : 0;
                }
                $lab->data_quality_index = (0.5 * $lab->data_completeness_full) + (0.2 * $lab->image_upload_indicator) + (0.3 * $lab->kpi_update);

                if ($lab->data_quality_index > 90) {
                    $lab->data_quality="excellent";
                    $lab->data_quality_description = "البيانات دقيقة، كاملة، ومحدثة باستمرار. تعكس مستوى عالٍ من الاحترافية والموثوقية.";
                    
                } elseif ($lab->data_quality_index > 80) {
                    $lab->data_quality="very good";
                    $lab->data_quality_description = "البيانات جيدة بشكل عام، لكن قد توجد بعض النواقص الطفيفة في الاكتمال أو الحداثة.";
                } elseif ($lab->data_quality_index > 70) {
                    $lab->data_quality="good";
                    $lab->data_quality_description = "البيانات جيدة، ولكن هناك بعض المجالات التي تحتاج إلى تحسين.";
                } elseif ($lab->data_quality_index > 60) {
                    $lab->data_quality="acceptable";
                    $lab->data_quality_description = "البيانات مقبولة، لكنها تحتاج إلى مجهود كبير لتحسينها. توجد ثغرات واضحة في الاكتمال أو الحداثة.";
                } else {
                    $lab->data_quality="poor";
                    $lab->data_quality_description ="البيانات غير موثوقة إلى حد كبير، وربما تكون قديمة أو غير مكتملة. لا يمكن الاعتماد عليها بشكل كامل.";
                }
            }
        }
        $universities = universitys::all();
        $faculties = $universityId
            ? fac_uni::where('uni_id', $universityId)->get()
            : fac_uni::all();

        return view('loggedTemp.reports', compact(
            'labs',
            'universities',
            'faculties',
            'universityId',
            'facultyId'
        ));
    }


}
