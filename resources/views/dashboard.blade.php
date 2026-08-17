@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1>Dashboard</h1>
        <p class="text-muted">Welcome to Bike Reconditioning House Management System</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Bikes</h5>
                <h2 class="card-text">{{ $stats['total_bikes'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">In Reconditioning</h5>
                <h2 class="card-text">{{ $stats['in_reconditioning'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Ready for Sale</h5>
                <h2 class="card-text">{{ $stats['ready_for_sale'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Sold</h5>
                <h2 class="card-text">{{ $stats['sold'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('bikes.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Add New Bike
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('customers.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-person-plus"></i> Add Customer
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('reports.index') }}" class="btn btn-info w-100">
                            <i class="bi bi-graph-up"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bikes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Bikes</h5>
                <a href="{{ route('bikes.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentBikes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>VIN</th>
                                <th>Make/Model</th>
                                <th>Status</th>
                                <th>Seller</th>
                                <th>Purchase Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBikes as $bike)
                            <tr>
                                <td>{{ $bike->vin }}</td>
                                <td>{{ $bike->make }} {{ $bike->model }} ({{ $bike->year }})</td>
                                <td>
                                    <span class="badge status-{{ $bike->status }}">
                                        {{ str_replace('_', ' ', ucfirst($bike->status)) }}
                                    </span>
                                </td>
                                <td>{{ $bike->seller->name ?? 'N/A' }}</td>
                                <td>{{ $bike->purchase_date->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('bikes.show', $bike) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">No bikes added yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection