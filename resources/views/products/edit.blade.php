@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Product Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <x-input name="code" label="Product Code" value="{{ old('code', $product->code) }}" readonly />
                        <x-input name="name" label="Product Name" value="{{ old('name', $product->name) }}" required />
                        <x-input name="description" label="Description" type="textarea" value="{{ old('description', $product->description) }}" />
                        <x-input name="price" label="Price" type="number" step="0.01" value="{{ old('price', $product->price) }}" required />
                        <x-input name="stock" label="Stock" type="number" value="{{ old('stock', $product->stock) }}" />
                        <x-input name="unit" label="Unit" value="{{ old('unit', $product->unit) }}" />
                        <x-input name="barcode" label="Barcode" value="{{ old('barcode', $product->barcode) }}" />
                        <x-input name="tax_rate" label="Tax Rate (%)" type="number" step="0.01" value="{{ old('tax_rate', $product->taxRate) }}" />
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $product->isActive) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

