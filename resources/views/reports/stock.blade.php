@extends('layouts.app')

@section('title', 'Stock Report')
@section('page-title', 'Stock Report')

@section('content')
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <h3>{{ $summary['total_products'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Stock Value</h5>
                    <h3>{{ number_format($summary['total_stock_value'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Low Stock Items</h5>
                    <h3 class="text-danger">{{ $summary['low_stock'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Stock Details</h5>
        </div>
        <div class="card-body">
            <x-table :headers="['Code', 'Product Name', 'Stock', 'Unit', 'Price', 'Stock Value']">
                @forelse($stockReport as $item)
                    <tr class="{{ $item['stock'] < 10 ? 'table-warning' : '' }}">
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>
                            <span class="badge {{ $item['stock'] < 10 ? 'bg-danger' : 'bg-success' }}">
                                {{ $item['stock'] }}
                            </span>
                        </td>
                        <td>{{ $item['unit'] }}</td>
                        <td>{{ number_format($item['price'], 2) }}</td>
                        <td>{{ number_format($item['stock_value'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </x-table>
        </div>
    </div>
@endsection

