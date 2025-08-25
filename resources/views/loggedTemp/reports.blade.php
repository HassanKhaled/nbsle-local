@extends('loggedTemp.head')
@section('loggedContent')


<div class="container-fluid">
    <h2 class="mb-4">Labs Report</h2>

    {{-- Filters --}}
    <form method="GET" action="{{ route('reports.index') }}" class="row mb-4">
        <div class="col-md-4">
            <select name="university_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- All Universities --</option>
                @foreach($universities as $uni)
                    <option value="{{ $uni->id }}" {{ $universityId == $uni->id ? 'selected' : '' }}>
                        {{ $uni->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select name="faculty_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- All Faculties --</option>
                @foreach($faculties as $fac)
                    <option value="{{ $fac->fac_id }}" {{ $facultyId == $fac->fac_id ? 'selected' : '' }}>
                        {{ $fac->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <button id="exportExcel" class="btn btn-success mb-3">Download Excel</button>


    <table class="table table-bordered table-striped table-responsive">
        <thead>
            <tr>
                <th>Lab Name</th>
                <th>Total Devices</th>
                <th>With Name</th>
                <th>With Image</th>
                <th>With Model</th>
                <th>With Cost</th>
                <th>With Price</th>
                <th>With Services</th>
                <th>With Description</th>
                <th>With Manufacturer</th>
                <th>With Manufacture Year</th>
                <th>With Maintenance Contract</th>
                <th>With Manufacture Country</th>
                <th>With Manufacture Website</th>
                <th>Available Devices</th>
                <th>Last Entry Date</th>
                <th>Image Upload Indicator</th>
                <th>Data Completeness (Average)</th>
                <th>KPI Update</th>
                <th>Data Quality Index</th>
                <th>Data Quality</th>
                <th>Data Quality Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($labs as $lab)
                <tr>
                    <td>{{ $lab->name }}</td>
                    <td>{{ $lab->devices_count }}</td>
                    <td>{{ $lab->devices_with_name_count }}</td>
                    <td>{{ $lab->devices_with_image_count }}</td>
                    <td>{{ $lab->devices_with_model_count }}</td>
                    <td>{{ $lab->devices_with_cost_count }}</td>
                    <td>{{ $lab->devices_with_price_count }}</td>
                    <td>{{ $lab->devices_with_services_count }}</td>
                    <td>{{ $lab->devices_with_description_count }}</td>
                    <td>{{ $lab->devices_with_manufacturer_count }}</td>
                    <td>{{ $lab->devices_with_manufacture_year_count }}</td>
                    <td>{{ $lab->devices_with_maintenance_contract_count }}</td>
                    <td>{{ $lab->devices_with_manufacture_country_count }}</td>
                    <td>{{ $lab->devices_with_manufacture_website_count }}</td>
                    <td>{{ $lab->available_devices_count }}</td>
                    <td>{{ $lab->last_entry_date ?? '-' }}</td>
                    <td>{{ number_format($lab->image_upload_indicator, 2) }} %</td>
                    <td>{{ number_format($lab->data_completeness_full, 2) }} % </td>
                    <td>{{ $lab->kpi_update }}</td>
                    <td>{{ number_format($lab->data_quality_index, 2) }} %</td>
                    <td>{{ $lab->data_quality }}</td>
                    <td>{{ $lab->data_quality_description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center">No labs found</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</div>
<!-- زر تحميل Excel -->

<!-- مكتبة SheetJS -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

<script>
document.getElementById("exportExcel").addEventListener("click", function () {
    // 1. حدد الجدول
    var table = document.querySelector("table");

    // 2. حول الجدول إلى Sheet
    var worksheet = XLSX.utils.table_to_sheet(table);

    // 3. صياغة الأعمدة اللي فيها نسب مئوية
    // نحدد الأعمدة (Image Upload Indicator, Data Completeness, Data Quality Index)
    let percentCols = ["Q", "R", "T"]; 
    // Q=17, R=18, T=20 حسب ترتيب الجدول عندك

    percentCols.forEach(col => {
        for (let row = 2; ; row++) { // من الصف الثاني (عشان فيه عناوين بالصف الأول)
            let cellRef = col + row;
            if (!worksheet[cellRef]) break; // لو مفيش خلية نوقف
            let val = worksheet[cellRef].v;

            if (!isNaN(val)) {
                worksheet[cellRef].v = val / 100; // نخليها بين 0 و 1
                worksheet[cellRef].t = "n";       // نوعها رقم
                worksheet[cellRef].z = "0.00%";   // صيغة كنسبة مئوية
            }
        }
    });

    // 4. أنشئ الملف
    var workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Labs Report");

    // 5. نزّل الملف
    XLSX.writeFile(workbook, "labs_report.xlsx");
});
</script>

@endsection

