@extends('loggedTemp.head')

@section('loggedContent')

<div class="container-fluid py-5 mt-5">

    <div class="card">
        <div class="card-header">
            <h4>Certificate Requests</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Workshop</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Days</th>
                        <th>Certificates</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th>image</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $req->workshop->workshop_ar_title ?? $req->workshop->workshop_en_title }}</td>
                            <td>{{ $req->name }}</td>
                            <td>{{ $req->email }}</td>
                            <td>
                                @if(is_array($req->days))
                                    {{ implode(', ', $req->days) }}
                                @else
                                    {{ $req->days }}
                                @endif
                            </td>
                            <td>{{ $req->cert_count }}</td>
                            <td>{{ $req->cost }} EGP</td>
                            <td>
                                <span class="badge badge-info">{{ $req->status }}</span>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $req->image_receipt) }}" target="_blank">
                                    View Receipt
                                </a>
                            <td>
                                {{-- Confirm Button --}}
                                @if($req->status != 'confirmed')
                                    <form method="POST" action="{{ route('certificate.confirm', $req->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control" 
                                                onchange="this.form.submit()">
                                            <option value="" selected>-- Select Action --</option>
                                            <option value="confirmed">Confirm</option>
                                            <option value="rejected">Reject</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-success">Confirmed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No requests found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
