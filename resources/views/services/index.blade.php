@extends('loggedTemp.head')

@section('loggedContent')

<div class="container">
    <h2>All Services</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Service Name</th>
                <th>Cost</th>
                <th>Service (Arabic)</th>
                <th>Cost (Arabic)</th>
                <th>Description</th>
                <th>Central</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->service_name }}</td>
                <td>{{ $service->cost }}</td>
                <td>{{ $service->service_arabic }}</td>
                <td>{{ $service->cost_arabic }}</td>
                <td>{{ $service->desc_service }}</td>
                <td>{{ $service->central ? 'Yes' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
