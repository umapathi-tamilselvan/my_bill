@extends('layouts.app')

@section('title', 'Bills')
@section('page-title', 'Bills')

@section('header-actions')
    <a href="{{ route('billing.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Bill
    </a>
@endsection

@section('content')
    <x-table :headers="['Bill Number', 'Date', 'Customer', 'Total Amount', 'Paid', 'Status', 'Actions']">
        @forelse($bills as $bill)
            <tr>
                <td>{{ $bill->bill_number }}</td>
                <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                <td>{{ $bill->customer->name ?? 'Walk-in' }}</td>
                <td>{{ number_format($bill->total_amount, 2) }}</td>
                <td>{{ number_format($bill->paid_amount, 2) }}</td>
                <td>
                    <span class="badge 
                        {{ $bill->payment_status === 'paid' ? 'bg-success' : ($bill->payment_status === 'partial' ? 'bg-warning' : 'bg-danger') }}">
                        {{ ucfirst($bill->payment_status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('billing.show', $bill->id) }}" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('billing.pdf', $bill->id) }}" class="btn btn-sm btn-primary" target="_blank">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No bills found.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-3">
        {{ $bills->links() }}
    </div>
@endsection

