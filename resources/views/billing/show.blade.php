@extends('layouts.app')

@section('title', 'Bill Details')
@section('page-title', 'Bill: ' . $bill->billNumber)

@section('header-actions')
    <a href="{{ route('billing.pdf', $bill->id) }}" class="btn btn-primary" target="_blank">
        <i class="bi bi-file-pdf"></i> Download PDF
    </a>
    <a href="{{ route('billing.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Bill Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Bill Number:</strong> {{ $bill->billNumber }}<br>
                            <strong>Date:</strong> {{ $bill->billDate }}<br>
                            <strong>Customer:</strong> {{ $billModel && $billModel->customer ? $billModel->customer->name : 'Walk-in' }}
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Payment Status:</strong> 
                            <span class="badge 
                                {{ $bill->paymentStatus === 'paid' ? 'bg-success' : ($bill->paymentStatus === 'partial' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($bill->paymentStatus) }}
                            </span>
                        </div>
                    </div>

                    <x-table :headers="['Product', 'Qty', 'Price', 'Tax', 'Discount', 'Total']">
                        @foreach($bill->items as $item)
                            <tr>
                                <td>{{ $item->productName }} ({{ $item->productCode }})</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unitPrice, 2) }}</td>
                                <td>{{ number_format($item->taxAmount, 2) }}</td>
                                <td>{{ number_format($item->discountAmount, 2) }}</td>
                                <td>{{ number_format($item->totalAmount, 2) }}</td>
                            </tr>
                        @endforeach
                    </x-table>

                    <div class="row mt-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <table class="table">
                                <tr>
                                    <th>Subtotal:</th>
                                    <td class="text-end">{{ number_format($bill->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Tax:</th>
                                    <td class="text-end">{{ number_format($bill->taxAmount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Discount:</th>
                                    <td class="text-end">{{ number_format($bill->discountAmount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td class="text-end"><strong>{{ number_format($bill->totalAmount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Paid:</th>
                                    <td class="text-end">{{ number_format($bill->paidAmount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Balance:</th>
                                    <td class="text-end">{{ number_format($bill->totalAmount - $bill->paidAmount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($bill->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong> {{ $bill->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

