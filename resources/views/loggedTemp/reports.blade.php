@extends('loggedTemp.head')
@section('loggedContent')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .quality-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: bold;
        color: white;
        font-size: 0.95rem;
    }

    .quality-badge .score {
        background: rgba(0,0,0,0.4);
        padding: 3px 10px;
        border-radius: 12px;
        margin-right: 4px;
    }

    .stars {
        display: inline-flex;
        gap: 1px;
    }

    .stars i {
        font-size: 0.8rem;
    }

    .star-filled { color: #6DB136; }
    .star-empty { color: #555; }

    .quality { 
        background: #1E4356;
        border: 2px solid #6DB136;
    }

    /* ====== Header Row Container ====== */
    .header-row-container {
        display: flex;
        margin-bottom: 0;
        background: white;
        border-radius: 8px 8px 0 0;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Lab Name width 25% */
    .lab-name-header {
        background: #2c3e50;
        color: white;
        font-weight: bold;
        text-align: left;
        padding: 14px 15px;
        min-width: 200px;
        width: 25%;
        max-width: 25%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        border-right: 2px solid #1E4356;
    }

    /* Lab Name inside table */
    .lab-name-cell {
        background: #2c3e50 !important;
        color: white !important;
        font-weight: bold;
        text-align: left !important;
        padding: 12px 15px !important;
        min-width: 200px;
        width: 25%;
        max-width: 25%;
        position: sticky;
        left: 0;
        z-index: 10;
    }

    /* Remaining Columns */
    .column-groups-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        width: 75%;
        flex-grow: 1;
    }

    .column-group {
        background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        order: -1;
        cursor: pointer;
        position: relative;
        border-radius: 12px 12px 0 0;
        margin: 0 4px;
        transform: scale(0.96);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .column-group:hover:not(.active) {
        transform: scale(0.98);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        background: linear-gradient(135deg, #3d566e 0%, #34495e 100%);
    }

    .column-group.active {
        display: flex;
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        animation: slideDown 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        order: 0 !important;
        margin: 0;
        border-radius: 0;
        transform: scale(1);
        box-shadow: 0 6px 16px rgba(39, 174, 96, 0.3);
    }

    .column-group-content {
        display: flex;
        width: 100%;
        gap: 0;
    }

    .column-tab {
        background: transparent;
        color: white;
        padding: 14px 10px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 3px solid transparent;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .column-group:not(.active) .column-tab {
        border-right: 1px solid rgba(255,255,255,0.1);
    }

    .column-tab:last-child {
        border-right: none;
    }

    .column-group.active .column-tab {
        background: rgba(0,0,0,0.1);
        border-right: 1px solid rgba(255,255,255,0.2);
        border-bottom: 3px solid rgba(255,255,255,0.4);
    }

    .column-group.active .column-tab:last-child {
        border-right: none;
    }

    /* Main Table */
    .main-table-container {
        background: white;
        border-radius: 0 0 8px 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .main-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .main-table td {
        vertical-align: middle;
        text-align: center;
        padding: 12px 10px;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
    }

    .main-table tbody tr:nth-child(even) td:not(.lab-name-cell) {
        background: #ffffff;
    }

    .pagination-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        margin-top: 2rem;
        padding: 1.2rem;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .pagination-controls button {
        padding: 12px 35px;
        font-weight: bold;
        font-size: 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .pagination-controls button:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none !important;
    }

    .pagination-controls .page-info {
        color: white;
        font-size: 1.4rem;
        font-weight: bold;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .btn-prev, .btn-next {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
    }

    .btn-prev:hover:not(:disabled), .btn-next:hover:not(:disabled) {
        background: linear-gradient(135deg, #229954 0%, #1e8449 100%);
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
    }

    .table-page {
        display: none;
    }

    .table-page.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }

    .table-responsive {
        overflow-x: auto;
    }

    @keyframes slideDown {
        from { 
            opacity: 0; 
            transform: translateY(-15px) scale(0.98);
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* ====== RESPONSIVE DESIGN ====== */
    @media (max-width: 1400px) {
        .column-tab {
            font-size: 0.85rem;
            padding: 12px 8px;
        }
        
        .lab-name-header, .lab-name-cell {
            min-width: 180px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 1200px) {
        .header-row-container {
            flex-direction: column;
        }

        .lab-name-header {
            width: 100%;
            max-width: 100%;
            border-right: none;
            border-bottom: 2px solid #1E4356;
        }

        .column-groups-wrapper {
            width: 100%;
        }

        .column-tab {
            font-size: 0.8rem;
            padding: 10px 6px;
        }

        .lab-name-cell {
            position: relative;
            width: 100%;
            max-width: 100%;
        }

        .main-table {
            table-layout: auto;
        }
    }

    @media (max-width: 992px) {
        .pagination-controls {
            gap: 15px;
            padding: 1rem;
        }

        .pagination-controls button {
            padding: 10px 25px;
            font-size: 0.9rem;
        }

        .pagination-controls .page-info {
            font-size: 1.2rem;
        }

        .column-tab {
            font-size: 0.75rem;
            padding: 10px 5px;
        }

        .quality-badge {
            font-size: 0.85rem;
            padding: 5px 10px;
        }
    }

    @media (max-width: 768px) {
        .header-row-container {
            border-radius: 6px 6px 0 0;
        }

        .column-group {
            margin: 0 2px;
        }

        .column-group.active {
            margin: 0;
        }

        .column-group-content {
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .column-tab {
            min-width: 80px;
            font-size: 0.7rem;
            padding: 8px 4px;
            white-space: nowrap;
        }

        .main-table td {
            padding: 8px 6px;
            font-size: 0.85rem;
        }

        .pagination-controls {
            flex-direction: column;
            gap: 15px;
        }

        .pagination-controls button {
            width: 100%;
            max-width: 250px;
        }

        .quality-badge {
            flex-direction: column;
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .quality-badge .score {
            margin-right: 0;
            margin-bottom: 2px;
        }
    }

    @media (max-width: 576px) {
        .column-tab {
            min-width: 70px;
            font-size: 0.65rem;
            padding: 8px 3px;
        }

        .lab-name-header, .lab-name-cell {
            font-size: 0.85rem;
            padding: 10px;
        }

        .main-table td {
            padding: 6px 4px;
            font-size: 0.75rem;
        }

        .stars i {
            font-size: 0.7rem;
        }

        h2.fw-bold {
            font-size: 1.5rem;
        }
    }

    /* Improve scrollbar for horizontal scroll */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #27ae60;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #229954;
    }
</style>

<div class="container-fluid py-4">

    {{-- Page Title --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="fw-bold">📊 Labs Report</h2>
        <button id="exportExcel" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Download Excel
        </button>
    </div>

    @if (auth()->user()->role_id == 1)
        <form method="GET" action="{{ route('university.ranks') }}">
            <button type="submit" class="btn btn-success mb-3">📊 Rank</button>
        </form>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3" id="filterForm">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">University</label>
                    <select name="university_id" class="form-control" onchange="this.form.submit()" id="universitySelect">
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
                    <select name="faculty_id" class="form-control" onchange="this.form.submit()" id="facultySelect">
                        <option value="all" {{ $facultyId == 'all' ? 'selected' : '' }}>-- All Faculties --</option>
                        @foreach($faculties as $fac)
                            <option value="{{ $fac->fac_id }}" {{ $facultyId == $fac->fac_id ? 'selected' : '' }}>
                                {{ $fac->name }}
                            </option>
                        @endforeach
                        @if($faculties->isEmpty())
                            <option value="central" {{ $facultyId == 'central' ? 'selected' : '' }}>Central</option>
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Overall Stats --}}
    @if($stats)
        <div class="alert alert-info">
            <strong>Overall Statistics:</strong>
            Data Quality Index: {{ number_format($stats['totalDataQualityIndex'], 2) }}% |
            Total Devices: {{ $stats['totalDevices'] }} |
            Total Labs: {{ $stats['totalLabs'] }}
        </div>
    @endif

    @if($labs->isNotEmpty())

        <div class="header-row-container">
            <div class="lab-name-header">Lab Name</div>
            
            <div class="column-groups-wrapper">
                <!-- Group 1 -->
                <div class="column-group active" id="group1" data-page="1">
                    <div class="column-group-content">
                        <div class="column-tab">Data Quality Index</div>
                        <div class="column-tab">Data Quality</div>
                        <div class="column-tab">Description</div>
                        <div class="column-tab">Proposed Actions</div>
                        <div class="column-tab">Image Indicator</div>
                        <div class="column-tab">Data Completeness</div>
                    </div>
                </div>

                <!-- Group 2 -->
                <div class="column-group" id="group2" data-page="2">
                    <div class="column-group-content">
                        <div class="column-tab">Data Update</div>
                        <div class="column-tab">Total Devices</div>
                        <div class="column-tab">Name</div>
                        <div class="column-tab">Image</div>
                        <div class="column-tab">Model</div>
                        <div class="column-tab">Cost</div>
                        <div class="column-tab">Price</div>
                        <div class="column-tab">Services</div>
                    </div>
                </div>

                <!-- Group 3 -->
                <div class="column-group" id="group3" data-page="3">
                    <div class="column-group-content">
                        <div class="column-tab">Description</div>
                        <div class="column-tab">Manufacturer</div>
                        <div class="column-tab">Year</div>
                        <div class="column-tab">Maintenance</div>
                        <div class="column-tab">Country</div>
                        <div class="column-tab">Website</div>
                        <div class="column-tab">Available Devices</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== PAGE 1 ===== --}}
        <div class="table-page active" id="page1">
            <div class="main-table-container">
                <div class="table-responsive">
                    <table class="main-table">
                        <tbody>
                            @foreach($labs as $lab)
                                @php
                                    $stars = match($lab->data_quality) {
                                        'ممتاز' => 5,
                                        'جيد جداً' => 4,
                                        'مقبول' => 3,
                                        'ضعيف' => 2,
                                        default => 0,
                                    };
                                @endphp
                                <tr>
                                    <td class="lab-name-cell">{{ $lab->name }}</td>
                                    <td>
                                        <div class="quality-badge quality">
                                            <span class="score">{{ number_format($lab->data_quality_index, 1) }}</span>
                                            <div class="stars">
                                                @for($i = 0; $i < 5; $i++)
                                                    <i class="bi bi-star-fill {{ $i < $stars ? 'star-filled' : 'star-empty' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $lab->data_quality }}</td>
                                    <td>{{ Str::limit($lab->data_quality_description, 20, '...') }}</td>
                                    <td>{{ Str::limit($lab->Proposed_proposal, 20, '...') }}</td>
                                    <td>{{ number_format($lab->image_upload_indicator, 2) }}%</td>
                                    <td>{{ number_format($lab->data_completeness_full, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ===== PAGE 2 ===== --}}
        <div class="table-page" id="page2">
            <div class="main-table-container">
                <div class="table-responsive">
                    <table class="main-table">
                        <tbody>
                            @foreach($labs as $lab)
                                <tr>
                                    <td class="lab-name-cell">{{ $lab->name }}</td>
                                    <td>{{ number_format($lab->kpi_update, 2) }}%</td>
                                    <td>{{ $lab->devices_count }}</td>
                                    <td>{{ $lab->devices_with_name_count }}</td>
                                    <td>{{ $lab->devices_with_image_count }}</td>
                                    <td>{{ $lab->devices_with_model_count }}</td>
                                    <td>{{ $lab->devices_with_cost_count }}</td>
                                    <td>{{ $lab->devices_with_price_count }}</td>
                                    <td>{{ $lab->devices_with_services_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ===== PAGE 3 ===== --}}
        <div class="table-page" id="page3">
            <div class="main-table-container">
                <div class="table-responsive">
                    <table class="main-table">
                        <tbody>
                            @foreach($labs as $lab)
                                <tr>
                                    <td class="lab-name-cell">{{ $lab->name }}</td>
                                    <td>{{ $lab->devices_with_description_count }}</td>
                                    <td>{{ $lab->devices_with_manufacturer_count }}</td>
                                    <td>{{ $lab->devices_with_manufacture_year_count }}</td>
                                    <td>{{ $lab->devices_with_maintenance_contract_count }}</td>
                                    <td>{{ $lab->devices_with_manufacture_country_count }}</td>
                                    <td>{{ $lab->devices_with_manufacture_website_count }}</td>
                                    <td>{{ $lab->available_devices_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination Controls --}}
        <div class="pagination-controls">
            <button class="btn-prev" id="prevBtn">
                <i class="bi bi-chevron-left"></i> Previous
            </button>
            <span class="page-info"><span id="currentPage">1</span> / 3</span>
            <button class="btn-next" id="nextBtn">
                Next <i class="bi bi-chevron-right"></i>
            </button>
        </div>

    @else
        <div class="alert alert-warning">No labs found</div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
// Enhanced Pagination System
const PageManager = {
    currentPage: 1,
    totalPages: 3,
    
    init() {
        this.bindEvents();
        this.showPage(1);
    },
    
    bindEvents() {
        // Previous button
        document.getElementById('prevBtn')?.addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.navigateToPage(this.currentPage - 1);
            }
        });
        
        // Next button
        document.getElementById('nextBtn')?.addEventListener('click', () => {
            if (this.currentPage < this.totalPages) {
                this.navigateToPage(this.currentPage + 1);
            }
        });
        
        // Click on column groups
        document.querySelectorAll('.column-group').forEach(group => {
            group.addEventListener('click', (e) => {
                const pageNum = parseInt(group.getAttribute('data-page'));
                if (pageNum && pageNum !== this.currentPage) {
                    this.navigateToPage(pageNum);
                }
            });
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' && this.currentPage > 1) {
                this.navigateToPage(this.currentPage - 1);
            } else if (e.key === 'ArrowRight' && this.currentPage < this.totalPages) {
                this.navigateToPage(this.currentPage + 1);
            }
        });
    },
    
    navigateToPage(pageNum) {
        if (pageNum < 1 || pageNum > this.totalPages || pageNum === this.currentPage) return;
        
        this.currentPage = pageNum;
        this.showPage(pageNum);
    },
    
    showPage(pageNum) {
        // Update table pages
        document.querySelectorAll('.table-page').forEach(page => {
            page.classList.remove('active');
        });
        document.getElementById(`page${pageNum}`)?.classList.add('active');
        
        // Update column groups
        document.querySelectorAll('.column-group').forEach(group => {
            group.classList.remove('active');
        });
        document.getElementById(`group${pageNum}`)?.classList.add('active');
        
        // Update pagination display
        const currentPageEl = document.getElementById('currentPage');
        if (currentPageEl) {
            currentPageEl.textContent = pageNum;
        }
        
        // Update button states
        this.updateButtonStates();
        
        // Scroll to top of table
        document.querySelector('.header-row-container')?.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    },
    
    updateButtonStates() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        if (prevBtn) {
            prevBtn.disabled = this.currentPage === 1;
        }
        
        if (nextBtn) {
            nextBtn.disabled = this.currentPage === this.totalPages;
        }
    }
};

// Excel Export Functionality
const ExcelExporter = {
    init() {
        document.getElementById("exportExcel")?.addEventListener("click", () => {
            this.exportToExcel();
        });
    },
    
    exportToExcel() {
        const workbook = XLSX.utils.book_new();
        const pageNames = ["Data Quality", "Device Details", "Device Info"];
        
        document.querySelectorAll('.table-page table').forEach((table, index) => {
            const worksheet = XLSX.utils.table_to_sheet(table);
            const sheetName = pageNames[index] || `Page ${index + 1}`;
            XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
        });
        
        const filename = `labs_report_${new Date().toISOString().split('T')[0]}.xlsx`;
        XLSX.writeFile(workbook, filename);
    }
};

// Form Management
const FormManager = {
    init() {
        const universitySelect = document.getElementById("universitySelect");
        
        if (universitySelect) {
            universitySelect.addEventListener("change", () => {
                const facultySelect = document.getElementById("facultySelect");
                if (facultySelect) {
                    facultySelect.value = "all";
                }
            });
        }
    }
};

// Initialize all modules when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    PageManager.init();
    ExcelExporter.init();
    FormManager.init();
});
</script>

@endsection