@extends('loggedTemp.head')
@section('loggedContent')
<div class="container py-5 mt-5">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('errors') && count(session('errors')))
    <div class="alert alert-warning">
        <strong>Some rows were skipped:</strong>
        <ul class="mb-0">
            @foreach(session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" 
      action="{{ route('attendance.import', $workshop->id) }}"
      enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
    <label>Lecture / Day</label>
    <select name="day_number" class="form-control" required>
        <option value="">-- Select Day --</option>
        @for($i = 1; $i <= $workshop->no_lecturers; $i++)
            <option value="{{ $i }}">Day {{ $i }}</option>
        @endfor
    </select>
</div>


    <div class="mb-3">
        <label class="form-label">Attendance File (CSV / Excel)</label>
        <input type="file" name="file" class="form-control" required>
        <small class="text-muted">
            File must contain only <strong>national_id</strong> column
        </small>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-success">
            Import Attendance
        </button>

        <a href="{{ route('attendance.template') }}" 
           class="btn btn-outline-primary">
            Download Template
        </a>
    </div>
</form>
<table class="table table-bordered mt-4"        >
    <thead>
        <tr>
            <th>User Name</th>
            <th>National ID</th>
            <th>Day Number</th>
            <th>workshop</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->user->national_id }}</td>
                <td>Day{{ $attendance->day_number }}</td>
                <td>{{ $attendance->workshop->workshop_ar_title ??   $attendance->workshop->workshop_en_title}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</div>
@endsection
