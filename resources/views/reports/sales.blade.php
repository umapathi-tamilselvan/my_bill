@extends('layouts.app')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.sales') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Bills</h5>
                    <h3>{{ $summary['total_bills'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Sales</h5>
                    <h3>{{ number_format($summary['total_sales'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Paid</h5>
                    <h3>{{ number_format($summary['total_paid'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Tax</h5>
                    <h3>{{ number_format($summary['total_tax'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Bill Details</h5>
        </div>
        <div class="card-body">
            <x-table :headers="['Bill Number', 'Date', 'Customer', 'Subtotal', 'Tax', 'Discount', 'Total', 'Paid', 'Status']">
                @forelse($bills as $bill)
                    <tr>
                        <td>{{ $bill->bill_number }}</td>
                        <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                        <td>{{ $bill->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ number_format($bill->subtotal, 2) }}</td>
                        <td>{{ number_format($bill->tax_amount, 2) }}</td>
                        <td>{{ number_format($bill->discount_amount, 2) }}</td>
                        <td>{{ number_format($bill->total_amount, 2) }}</td>
                        <td>{{ number_format($bill->paid_amount, 2) }}</td>
                        <td>
                            <span class="badge 
                                {{ $bill->payment_status === 'paid' ? 'bg-success' : ($bill->payment_status === 'partial' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($bill->payment_status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No bills found for the selected period.</td>
                    </tr>
                @endforelse
            </x-table>
        </div>
    </div>
@endsection

