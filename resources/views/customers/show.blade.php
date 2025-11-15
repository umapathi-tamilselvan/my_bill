@extends('layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Customer Information</h5>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Code:</th>
                            <td>{{ $customer->code }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $customer->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $customer->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Opening Balance:</th>
                            <td>{{ number_format($customer->openingBalance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge {{ $customer->isActive ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $customer->isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    <div class="d-flex gap-2">
                        <a href="{{ route('customers.ledger', $customer->id) }}" class="btn btn-info">View Ledger</a>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

