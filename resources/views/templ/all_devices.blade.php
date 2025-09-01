@extends('templ.head')

@section('tmplt-contnt')
<div class="container py-4">

    <h2 class="mb-4 text-center fw-bold">📡 All Devices</h2>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-striped table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Device Name</th>
                        <th>Type</th>
                        <th>Lab</th>
                        <th>University</th>
                        <th>Faculty</th>
                        <th>Views</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($allDevices as $index => $device)
                    <tr>
                        <td>{{ ($allDevices->currentPage() - 1) * $allDevices->perPage() + $index + 1 }}</td>
                        <td>{{ $device->name!=null?$device->name:$device->Arabicname }}</td>
                        <td>
                            @if($device instanceof \App\Models\UniDevices)
                                <span class="badge bg-primary">Central</span>
                            @else
                                <span class="badge bg-success">Normal</span>
                            @endif
                        </td>
                        <td>{{ $device->lab->name ?? '-' }}</td>
                        <td>{{ $device->lab->university->name ?? '-' }}</td>
                        <td>{{ $device->lab->faculty->name ?? '-' }}</td>
                        <td>{{ $device->views ?? 0 }}</td>
                        <td>
                            <a href="{{ route('browsedevice', [
                                'dev_id'   => $device->id,
                                'lab_id'   => $device->lab->id ?? 0,
                                'central'  => $device instanceof \App\Models\UniDevices ? 1 : 0,
                                'uni_id'   => $device->lab->uni_id ?? 0,
                                'uniname'  => $device->lab->uni->name ?? 'Unknown',
                                'facID'    => $device->lab->fac_id ?? null,
                                'facName'  => $device->lab->faculty->name ?? null
                            ]) }}" class="btn btn-sm btn-outline-info">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-muted">No devices found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $allDevices->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>
@endsection
