@extends('loggedTemp.head')
@section('loggedContent')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <small>Total Records: <strong>{{ $mailLogs->total() }}</strong></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class='bx bx-filter-alt'></i> Filter Mail Logs
                </h5>
            </div>
            <div class="card-block">
                <form action="{{ route('mail-logs') }}" method="GET" class="row">
                    <!-- Search -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="search">Search</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Email or subject..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Status Filter -->
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="date_from">Date From</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>

                    <!-- Date To -->
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="date_to">Date To</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>

                    <!-- Buttons -->
                    <div class="col-lg-3 col-md-12 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-round me-2">
                            <i class='bx bx-search-alt'></i> Filter
                        </button>
                        <a href="{{ route('mail-logs') }}" class="btn btn-secondary btn-round">
                            <i class='bx bx-reset'></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Sent</h6>
                            <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Success</h6>
                            <h3 class="mb-0 text-success">{{ $stats['success'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Failed</h6>
                            <h3 class="mb-0 text-danger">{{ $stats['failed'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mail Logs Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Mail Logs History</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 20%">From</th>
                            <th style="width: 20%">To</th>
                            <th style="width: 25%">Subject</th>
                            <th style="width: 10%" class="text-center">Status</th>
                            <th style="width: 15%">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mailLogs as $log)
                        <tr>
                            <td>{{ $mailLogs->firstItem() + $loop->index }}</td>
                            <td>
                                <small class="text-truncate d-block" style="max-width: 200px;" 
                                       title="{{ $log->from_email }}">
                                    {{ $log->from_email }}
                                </small>
                            </td>
                            <td>
                                <small class="text-truncate d-block" style="max-width: 200px;" 
                                       title="{{ $log->to_email }}">
                                    {{ $log->to_email }}
                                </small>
                            </td>
                            <td>
                                <span class="text-truncate d-block" style="max-width: 300px;" 
                                      title="{{ $log->subject }}">
                                    {{ $log->subject }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($log->status == 'success')
                                    <span class="badge bg-success">
                                     Success
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $log->created_at->format('Y-m-d') }}<br>
                                    <span class="text-muted">{{ $log->created_at->format('H:i:s') }}</span>
                                </small>
                            </td>
                        </tr>

                        <!-- Modal for each log -->
                        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Mail Log Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-3"><strong>ID:</strong></div>
                                            <div class="col-md-9">{{ $log->id }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3"><strong>From Email:</strong></div>
                                            <div class="col-md-9">{{ $log->from_email }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3"><strong>To Email:</strong></div>
                                            <div class="col-md-9">{{ $log->to_email }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3"><strong>Subject:</strong></div>
                                            <div class="col-md-9">{{ $log->subject }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3"><strong>Status:</strong></div>
                                            <div class="col-md-9">
                                                @if($log->status == 'success')
                                                    <span class="badge bg-success">Success</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($log->error_message)
                                        <div class="row mb-3">
                                            <div class="col-md-3"><strong>Error Message:</strong></div>
                                            <div class="col-md-9">
                                                <div class="alert alert-danger mb-0">
                                                    {{ $log->error_message }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row mb-3">
                                            <div class="col-md-3"><strong>Sent At:</strong></div>
                                            <div class="col-md-9">{{ $log->created_at->format('F d, Y H:i:s') }}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3"><strong>Last Updated:</strong></div>
                                            <div class="col-md-9">{{ $log->updated_at->format('F d, Y H:i:s') }}</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No mail logs found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($mailLogs->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $mailLogs->firstItem() }} to {{ $mailLogs->lastItem() }} of {{ $mailLogs->total() }} entries
                </div>
                <div>
                    {{ $mailLogs->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.875rem;
            padding: 0.5rem;
        }
    }
</style>
@endpush

@endsection