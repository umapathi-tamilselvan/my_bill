@extends('layouts.app')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Product Information</h5>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Code:</th>
                            <td>{{ $product->code }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $product->description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>{{ number_format($product->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Stock:</th>
                            <td>{{ $product->stock }} {{ $product->unit }}</td>
                        </tr>
                        <tr>
                            <th>Barcode:</th>
                            <td>{{ $product->barcode ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Tax Rate:</th>
                            <td>{{ $product->taxRate }}%</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge {{ $product->isActive ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection

