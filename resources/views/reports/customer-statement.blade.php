@extends('layouts.app')

@section('title', 'Customer Statement')
@section('page-title', 'Customer Statement')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.customer-statement') }}" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Select Customer</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">-- Select Customer --</option>
                        @foreach(\App\Models\Customer::active()->get() as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Generate Statement</button>
                </div>
            </form>
        </div>
    </div>

    @if($ledger)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Opening Balance:</strong> {{ number_format($ledger['opening_balance'], 2) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Total Bills:</strong> {{ $ledger['bills']->count() }}
                    </div>
                    <div class="col-md-3">
                        <strong>Total Amount:</strong> {{ number_format($ledger['bills']->sum('total_amount'), 2) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Current Balance:</strong> 
                        <span class="badge {{ $ledger['total_balance'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ number_format($ledger['total_balance'], 2) }}
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
                        <td class="text-end"><strong>{{ number_format($ledger['opening_balance'], 2) }}</strong></td>
                    </tr>
                    @foreach($ledger['bills'] as $bill)
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
    @endif
@endsection

