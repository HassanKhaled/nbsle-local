@extends('loggedTemp.head')
@section('loggedContent')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-fluid py-4">

    {{-- Page Title --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">📊 Labs Report</h2>
        <button id="exportExcel" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Download Excel
        </button>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">University</label>
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
                    <label class="form-label fw-semibold">Faculty</label>
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
        </div>
    </div>

    {{-- Count --}}
   <div class="d-flex flex-wrap">
     <h5 class="mb-3 ml-3">
        <span class="badge bg-primary fs-6">Total Devices: {{ $totalDevices }}</span>
    </h5>
    <h5 class="mb-3 ml-3">
        <span class="badge bg-primary fs-6">Total Devices with Name: {{ $totalDevicesName }}</span>
    </h5>
    <h5 class="mb-3 ml-3">
        <span class="badge bg-primary fs-6">Image Upload Indicator: {{ $imageIndicator }}</span>
    </h5>
    <h5 class="mb-3 ml-3">
        <span class="badge bg-primary fs-6">Data Completeness: {{ $dataCompleteness }}</span>
    </h5>
   </div>
    {{-- Table --}}
    <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center sticky-top">
                <tr>
                    <th>Lab Name</th>
                    <th>Image Indicator</th>
                    <th>Data Completeness</th>
                    <th>Data Update</th>
                    <th>Data Quality Index</th>
                    <th>Data Quality</th>
                    <th>Description</th>
                    <th>Proposed Actions</th>
                    <th>Total Devices</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Model</th>
                    <th>Cost</th>
                    <th>Price</th>
                    <th>Services</th>
                    <th>Description</th>
                    <th>Manufacturer</th>
                    <th>Year</th>
                    <th>Maintenance</th>
                    <th>Country</th>
                    <th>Website</th>
                    <th>Available Devices</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labs as $lab)
                    @php
                        $rowClass = '';
                        switch(strtolower($lab->data_quality)) {
                            case 'excellent':
                                $rowClass = 'table-success'; // green
                                break;
                            case 'very good':
                                $rowClass = 'table-warning'; // yellow
                                break;
                            case 'poor':
                                $rowClass = 'table-danger'; // red
                                break;
                            case 'acceptable':
                                $rowClass = 'table-secondary'; // gray
                                break;

                        }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="fw-semibold">{{ $lab->name }}</td>
                        <td class="text-end">{{ number_format($lab->image_upload_indicator, 2) }}%</td>
                        <td class="text-end">{{ number_format($lab->data_completeness_full, 2) }}%</td>
                        <td class="text-end">{{ $lab->kpi_update }}%</td>
                        <td class="text-end">{{ number_format($lab->data_quality_index, 2) }}%</td>
                        <td>
                            @php
                                $icon = '';
                                $color = '';
                                switch(strtolower($lab->data_quality)) {
                                    case 'excellent':
                                        $icon = '🥇';  
                                        break;
                                    case 'very good':
                                        $icon = '👍';
                                        break;
                                    case 'acceptable':
                                        $icon = '✔️';
                                        break;
                                    case 'poor':
                                        $icon = '⚠️';
                                        break;
                                }
                            @endphp

                            <span style="font-size: 1.5em;">{{ $icon }}</span>
                            {{ $lab->data_quality }}
                        </td>
                        <td>{{ $lab->data_quality_description }}</td>
                        <td>{{ $lab->Proposed_proposal }}</td>
                        <td class="text-center">{{ $lab->devices_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_name_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_image_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_model_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_cost_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_price_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_services_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_description_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_manufacturer_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_manufacture_year_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_maintenance_contract_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_manufacture_country_count }}</td>
                        <td class="text-center">{{ $lab->devices_with_manufacture_website_count }}</td>
                        <td class="text-center">{{ $lab->available_devices_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="23" class="text-center text-muted">No labs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
document.getElementById("exportExcel").addEventListener("click", function () {
    var table = document.querySelector("table");
    var worksheet = XLSX.utils.table_to_sheet(table);

    var workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Labs Report");
    XLSX.writeFile(workbook, "labs_report.xlsx");
});
</script>

@endsection
