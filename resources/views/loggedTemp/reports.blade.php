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
                        <option value="all" {{ $universityId == 'all' ? 'selected' : '' }}>-- All Universities --</option>
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
                        <option value="all" {{ $facultyId == 'all' ? 'selected' : '' }}>-- All Faculties --</option>
                        @foreach($faculties as $fac)
                            <option value="{{ $fac->fac_id }}" {{ $facultyId == $fac->fac_id ? 'selected' : '' }}>
                                {{ $fac->name }}
                            </option>

                        @endforeach
                        @if($faculties->isEmpty())
                            <option value="central" {{ $facultyId == 'central' ? 'selected' : '' }}>
                                Central
                            </option>
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>

@if($stats)
    <div class=" table-responsive mt-4">
        <h4 class="mb-3">Statistics</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Total Devices</th>
                    <th>Total Labs</th>
                    <th>Devices With Name (%)</th>
                    <th>Devices With Image (%)</th>
                    <th>Devices With Model (%)</th>
                    <th>Devices With Price (%)</th>
                    <th>Devices With Cost (%)</th>
                    <th>Devices With Services (%)</th>
                    <th>Devices With Description (%)</th>
                    <th>Devices With Manufacturer (%)</th>
                    <th>Devices With Manufacture Year (%)</th>
                    <th>Devices With Maintenance Contract (%)</th>
                    <th>Devices With Manufacture Country (%)</th>
                    <th>Devices With Manufacture Website (%)</th>
                    <th>Available Devices (%)</th>
                    <th>Image Upload Indicator (%)</th>
                    <th>Data Completeness (%)</th>
                    <th>Total KPI Update (%)</th>
                    <th>Total Data Quality Index (%)</th>
                    <th>Total Data Quality (%)</th>
                    <th>Total Data Quality Description</th>
                    <th>Proposed Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $stats['totalDevices'] }}</td>
                    <td>{{ $stats['totalLabs'] }}</td>
                    <td>{{ number_format($stats['totalDevicesName'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesImage'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesModel'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesPrice'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesCost'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesServices'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesDescription'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesManufacturer'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesManufactureYear'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesMaintenanceContract'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesManufactureCountry'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDevicesManufactureWebsite'], 2) }}%</td>
                    <td>{{ number_format($stats['totalAvailableDevicesCount'], 2) }}%</td>
                    <td>{{ number_format($stats['imageIndicator'], 2) }}%</td>
                    <td>{{ number_format($stats['dataCompleteness'], 2) }}%</td>
                    <td>{{ number_format($stats['totalKPIUpdate'], 2) }}%</td>
                    <td>{{ number_format($stats['totalDataQualityIndex'], 2) }}%</td>
                    <td>
                         @php
                                $icon = '';
                                $color = '';
                                switch(strtolower($stats['totalDataQuality'])) {
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

                            <span style="font-size: 1.5em;">{{ $icon }}</span>{{ $stats['totalDataQuality'] }}</td>
                    <td>{{ $stats['totalDataQuality_description'] }}</td>
                    <td>{{ $stats['Proposed_proposal'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-warning mt-4">
        No data found for this university/faculty.
    </div>
@endif


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
