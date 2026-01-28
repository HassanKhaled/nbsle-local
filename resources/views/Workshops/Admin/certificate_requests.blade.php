@extends('loggedTemp.head')

@section('loggedContent')

<div class="container-fluid py-5 mt-5">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Certificate Requests</h4>

            {{-- Download Excel --}}
            <a href="{{ route('certificate.export') }}" class="btn btn-success">
                ⬇ Download Excel
            </a>
        </div>

        <div class="card-body">

            {{-- Bulk Action Form --}}
            <form method="POST" action="{{ route('certificate.bulkAction') }}">
                @csrf
                @method('PUT')

                <div class="mb-3 d-flex gap-2">
                    <select name="status" class="form-control w-auto" required>
                        <option value="">-- Bulk Action --</option>
                        <option value="confirmed">Confirm Selected</option>
                        <option value="rejected">Reject Selected</option>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        Apply
                    </button>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>#</th>
                            <th>Workshop</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Days</th>
                            <th>Certificates</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Image</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td>
                                    <input type="checkbox" 
                                           name="request_ids[]" 
                                           value="{{ $req->id }}"
                                           class="row-checkbox">
                                </td>

                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $req->workshop->workshop_ar_title ?? $req->workshop->workshop_en_title }}</td>
                                <td>{{ $req->name }}</td>
                                <td>{{ $req->email }}</td>
                                <td>
                                    {{ is_array($req->days) ? implode(', ', $req->days) : $req->days }}
                                </td>
                                <td>{{ $req->cert_count }}</td>
                                <td>{{ $req->cost }} EGP</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($req->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $req->image_receipt) }}" target="_blank">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    No requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>

        </div>
    </div>

</div>

{{-- Select All Script --}}
<script>
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>

@endsection
