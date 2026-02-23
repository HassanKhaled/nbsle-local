@extends('loggedTemp.head')

@section('loggedContent')

<div class="container-fluid py-5 mt-5">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Certificate Requests</h4>

            <a href="{{ route('certificate.export') }}" class="btn btn-success">
                ⬇ Download Excel
            </a>
        </div>

        <div class="card-body">

            @if(session('info'))
                <div class="alert alert-success">
                    {{ session('info') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Workshop</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th>Receipt</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $req->workshop->workshop_ar_title ?? $req->workshop->workshop_en_title }}</td>
                        <td>{{ $req->name }}</td>
                        <td>{{ $req->email }}</td>
                        <td>{{ $req->cost }} EGP</td>

                        <td>
                            <span class="badge 
                                @if($req->status == 'confirmed') bg-success
                                @elseif($req->status == 'rejected') bg-danger
                                @else bg-info
                                @endif">
                                {{ ucfirst($req->status) }}
                            </span>

                            @if($req->status == 'rejected' && $req->reason_rejection)
                                <div class="text-danger small mt-1">
                                    Reason: {{ $req->reason_rejection }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <a href="{{ asset('storage/' . $req->image_receipt) }}" target="_blank">
                                View
                            </a>
                        </td>
                        @if($req->status == 'pending')
                        <td>

                            {{-- Confirm --}}
                            <form action="{{ route('certificate.bulkAction', $req->id) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button class="btn btn-sm btn-success">
                                    Confirm
                                </button>
                            </form>

                            {{-- Reject --}}
                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="toggleReject({{ $req->id }})">
                                Reject
                            </button>

                            {{-- Reject Form --}}
                            <form id="rejectForm{{ $req->id }}"
                                  action="{{ route('certificate.bulkAction', $req->id) }}"
                                  method="POST"
                                  class="mt-2 d-none">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="status" value="rejected">

                                <input type="text"
                                       name="reason_rejection"
                                       class="form-control mb-2"
                                       placeholder="Enter rejection reason"
                                       required>

                                <button class="btn btn-sm btn-danger">
                                    Save
                                </button>
                            </form>

                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            No requests found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

<script>
function toggleReject(id) {
    let form = document.getElementById('rejectForm' + id);
    form.classList.toggle('d-none');
}
</script>

@endsection