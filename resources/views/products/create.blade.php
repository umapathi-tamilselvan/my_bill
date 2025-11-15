@extends('layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Product Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        
                        <x-input name="code" label="Product Code" value="{{ old('code') }}" />
                        <x-input name="name" label="Product Name" value="{{ old('name') }}" required />
                        <x-input name="description" label="Description" type="textarea" value="{{ old('description') }}" />
                        <x-input name="price" label="Price" type="number" step="0.01" value="{{ old('price') }}" required />
                        <x-input name="stock" label="Stock" type="number" value="{{ old('stock', 0) }}" />
                        <x-input name="unit" label="Unit" value="{{ old('unit', 'pcs') }}" />
                        <x-input name="barcode" label="Barcode" value="{{ old('barcode') }}" />
                        <x-input name="tax_rate" label="Tax Rate (%)" type="number" step="0.01" value="{{ old('tax_rate', 0) }}" />
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Product</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

