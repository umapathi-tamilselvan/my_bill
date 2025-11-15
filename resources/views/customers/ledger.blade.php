@extends('layouts.app')

@section('title', 'Customer Ledger')
@section('page-title', 'Customer Ledger: ' . $customer->name)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Opening Balance:</strong> {{ number_format($opening_balance, 2) }}
                        </div>
                        <div class="col-md-3">
                            <strong>Total Bills:</strong> {{ $bills->count() }}
                        </div>
                        <div class="col-md-3">
                            <strong>Total Amount:</strong> {{ number_format($bills->sum('total_amount'), 2) }}
                        </div>
                        <div class="col-md-3">
                            <strong>Total Paid:</strong> {{ number_format($bills->sum('paid_amount'), 2) }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <strong>Current Balance:</strong> 
                            <span class="badge {{ $total_balance >= 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ number_format($total_balance, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Transaction History</h5>
                </div>
                <div class="card-body">
                    <x-table :headers="['Date', 'Bill Number', 'Total Amount', 'Paid Amount', 'Balance', 'Status']">
                        <tr>
                            <td colspan="5"><strong>Opening Balance</strong></td>
                            <td class="text-end"><strong>{{ number_format($opening_balance, 2) }}</strong></td>
                        </tr>
                        @foreach($bills as $bill)
                            <tr>
                                <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('billing.show', $bill->id) }}">{{ $bill->bill_number }}</a>
                                </td>
                                <td>{{ number_format($bill->total_amount, 2) }}</td>
                                <td>{{ number_format($bill->paid_amount, 2) }}</td>
                                <td>{{ number_format($bill->total_amount - $bill->paid_amount, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $bill->payment_status === 'paid' ? 'bg-success' : ($bill->payment_status === 'partial' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($bill->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                </div>
            </div>
        </div>
    </div>
@endsection

